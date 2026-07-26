import type { ApiError } from "@/lib/types";

const ACCEPT_HEADER = "application/json";
const XSRF_COOKIE_NAME = "XSRF-TOKEN";
const XSRF_HEADER_NAME = "X-XSRF-TOKEN";

export class ApiRequestError extends Error {
  readonly status: number;
  readonly errors?: Record<string, string[]>;

  constructor(status: number, error: ApiError) {
    super(error.message);
    this.name = "ApiRequestError";
    this.status = status;
    this.errors = error.errors;
  }
}

type ApiFetchOptions = RequestInit & {
  withCsrf?: boolean;
};

function getApiOrigin(): string {
  const origin =
    typeof window === "undefined"
      ? process.env.INTERNAL_API_URL ?? process.env.NEXT_PUBLIC_API_URL
      : process.env.NEXT_PUBLIC_API_URL;

  if (!origin) {
    throw new Error("API URL is not configured.");
  }

  return origin.replace(/\/$/, "");
}

function readXsrfToken(): string | null {
  if (typeof document === "undefined") {
    return null;
  }

  const cookie = document.cookie
    .split("; ")
    .find((item) => item.startsWith(`${XSRF_COOKIE_NAME}=`));

  if (!cookie) {
    return null;
  }

  return decodeURIComponent(cookie.slice(XSRF_COOKIE_NAME.length + 1));
}

async function parseApiError(response: Response): Promise<ApiError> {
  try {
    const payload: unknown = await response.json();
    if (isApiError(payload)) {
      return payload;
    }
  } catch {
    // JSON ではないエラー本文は共通メッセージで扱う。
  }

  return { message: "リクエストに失敗しました。" };
}

function isApiError(value: unknown): value is ApiError {
  if (typeof value !== "object" || value === null || !("message" in value)) {
    return false;
  }

  if (typeof value.message !== "string") {
    return false;
  }

  if (!("errors" in value) || value.errors === undefined) {
    return true;
  }

  if (typeof value.errors !== "object" || value.errors === null) {
    return false;
  }

  return Object.values(value.errors).every(
    (messages) => Array.isArray(messages) && messages.every((message) => typeof message === "string"),
  );
}

async function initializeCsrfCookie(): Promise<void> {
  const response = await fetch(`${getApiOrigin()}/sanctum/csrf-cookie`, {
    credentials: "include",
    headers: { Accept: ACCEPT_HEADER },
  });

  if (!response.ok) {
    throw new ApiRequestError(response.status, await parseApiError(response));
  }
}

export async function apiFetch<T>(path: string, options: ApiFetchOptions = {}): Promise<T> {
  const { withCsrf = false, headers, ...requestOptions } = options;

  if (withCsrf) {
    await initializeCsrfCookie();
  }

  const requestHeaders = new Headers(headers);
  requestHeaders.set("Accept", ACCEPT_HEADER);

  if (withCsrf) {
    const token = readXsrfToken();
    if (token) {
      requestHeaders.set(XSRF_HEADER_NAME, token);
    }
  }

  const response = await fetch(`${getApiOrigin()}${path}`, {
    ...requestOptions,
    credentials: "include",
    headers: requestHeaders,
  });

  if (!response.ok) {
    throw new ApiRequestError(response.status, await parseApiError(response));
  }

  if (response.status === 204) {
    return undefined as T;
  }

  return (await response.json()) as T;
}
