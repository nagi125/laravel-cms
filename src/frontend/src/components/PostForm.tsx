"use client";

import { useState } from "react";
import { ApiRequestError } from "@/lib/api/client";
import type { PostInput } from "@/lib/api/posts";
import type { Post, PostStatus } from "@/lib/types";

const EMPTY_POST: PostInput = {
  title: "",
  slug: "",
  excerpt: null,
  body: "",
  status: "draft",
  published_at: null,
};

type PostFormProps = {
  initialPost?: Post;
  submitLabel: string;
  onSubmit: (input: PostInput) => Promise<void>;
};

function toDateTimeLocal(value: string | null): string {
  if (!value) {
    return "";
  }

  return value.slice(0, 16);
}

export function PostForm({ initialPost, submitLabel, onSubmit }: PostFormProps) {
  const [input, setInput] = useState<PostInput>(
    initialPost
      ? { ...initialPost, published_at: toDateTimeLocal(initialPost.published_at) || null }
      : EMPTY_POST,
  );
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [message, setMessage] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const update = <K extends keyof PostInput>(key: K, value: PostInput[K]) => {
    setInput((current) => ({ ...current, [key]: value }));
  };

  const fieldError = (field: string) => errors[field]?.[0];

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setErrors({});
    setMessage(null);
    setIsSubmitting(true);

    try {
      await onSubmit(input);
    } catch (error) {
      if (error instanceof ApiRequestError) {
        if (error.status === 401) {
          window.location.assign("/admin/login");
          return;
        }
        setErrors(error.errors ?? {});
        setMessage(error.message);
      } else {
        setMessage("記事を保存できませんでした。");
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form className="mt-6 space-y-5" onSubmit={handleSubmit}>
      {message ? <p className="text-sm text-red-700" role="alert">{message}</p> : null}
      <label className="block">タイトル<input className="mt-1 block w-full rounded border border-slate-300 p-2" required value={input.title} onChange={(event) => update("title", event.target.value)} /></label>
      {fieldError("title") ? <p className="text-sm text-red-700">{fieldError("title")}</p> : null}
      <label className="block">スラッグ<input className="mt-1 block w-full rounded border border-slate-300 p-2" required value={input.slug} onChange={(event) => update("slug", event.target.value)} /></label>
      {fieldError("slug") ? <p className="text-sm text-red-700">{fieldError("slug")}</p> : null}
      <label className="block">概要<textarea className="mt-1 block w-full rounded border border-slate-300 p-2" value={input.excerpt ?? ""} onChange={(event) => update("excerpt", event.target.value || null)} /></label>
      {fieldError("excerpt") ? <p className="text-sm text-red-700">{fieldError("excerpt")}</p> : null}
      <label className="block">本文<textarea className="mt-1 block min-h-64 w-full rounded border border-slate-300 p-2" required value={input.body} onChange={(event) => update("body", event.target.value)} /></label>
      {fieldError("body") ? <p className="text-sm text-red-700">{fieldError("body")}</p> : null}
      <label className="block">状態<select className="mt-1 block rounded border border-slate-300 p-2" value={input.status} onChange={(event) => update("status", event.target.value as PostStatus)}><option value="draft">下書き</option><option value="published">公開</option></select></label>
      <label className="block">公開日時<input className="mt-1 block rounded border border-slate-300 p-2" type="datetime-local" value={input.published_at ?? ""} onChange={(event) => update("published_at", event.target.value || null)} /></label>
      {fieldError("published_at") ? <p className="text-sm text-red-700">{fieldError("published_at")}</p> : null}
      <button className="rounded bg-slate-900 px-4 py-2 text-white disabled:opacity-50" disabled={isSubmitting} type="submit">{isSubmitting ? "保存中..." : submitLabel}</button>
    </form>
  );
}
