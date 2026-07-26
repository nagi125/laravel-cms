import { apiFetch } from "@/lib/api/client";
import type { User } from "@/lib/types";

type LoginInput = {
  email: string;
  password: string;
};

type ApiData<T> = { data: T };

export async function login(input: LoginInput): Promise<User> {
  const response = await apiFetch<ApiData<User>>("/api/auth/login", {
    method: "POST",
    withCsrf: true,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(input),
  });

  return response.data;
}

export async function logout(): Promise<void> {
  await apiFetch<void>("/api/auth/logout", { method: "POST", withCsrf: true });
}

export async function me(): Promise<User> {
  const response = await apiFetch<ApiData<User>>("/api/auth/me");
  return response.data;
}
