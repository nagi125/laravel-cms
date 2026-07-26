import { apiFetch } from "@/lib/api/client";
import type { Paginated, Post, PostStatus, PostSummary } from "@/lib/types";

export const DEFAULT_PER_PAGE = 15;

type ApiData<T> = { data: T };

export type PostInput = {
  title: string;
  slug: string;
  excerpt: string | null;
  body: string;
  status: PostStatus;
  published_at: string | null;
};

function queryString(params: Record<string, string | number | undefined>): string {
  const searchParams = new URLSearchParams();
  Object.entries(params).forEach(([key, value]) => {
    if (value !== undefined) {
      searchParams.set(key, String(value));
    }
  });

  const query = searchParams.toString();
  return query ? `?${query}` : "";
}

export async function getPublicPosts(page = 1): Promise<Paginated<PostSummary>> {
  return apiFetch<Paginated<PostSummary>>(
    `/api/posts${queryString({ page, per_page: DEFAULT_PER_PAGE })}`,
    { cache: "no-store" },
  );
}

export async function getPublicPost(slug: string): Promise<Post> {
  const response = await apiFetch<ApiData<Post>>(`/api/posts/${encodeURIComponent(slug)}`, {
    cache: "no-store",
  });
  return response.data;
}

export async function getAdminPosts(
  page = 1,
  status?: PostStatus,
): Promise<Paginated<PostSummary>> {
  return apiFetch<Paginated<PostSummary>>(
    `/api/admin/posts${queryString({ page, per_page: DEFAULT_PER_PAGE, status })}`,
  );
}

export async function getAdminPost(id: string): Promise<Post> {
  const response = await apiFetch<ApiData<Post>>(`/api/admin/posts/${encodeURIComponent(id)}`);
  return response.data;
}

export async function createPost(input: PostInput): Promise<Post> {
  const response = await apiFetch<ApiData<Post>>("/api/admin/posts", {
    method: "POST",
    withCsrf: true,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(input),
  });
  return response.data;
}

export async function updatePost(id: string, input: PostInput): Promise<Post> {
  const response = await apiFetch<ApiData<Post>>(`/api/admin/posts/${encodeURIComponent(id)}`, {
    method: "PUT",
    withCsrf: true,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(input),
  });
  return response.data;
}

export async function deletePost(id: string): Promise<void> {
  await apiFetch<void>(`/api/admin/posts/${encodeURIComponent(id)}`, {
    method: "DELETE",
    withCsrf: true,
  });
}
