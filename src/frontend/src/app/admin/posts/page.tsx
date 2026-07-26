"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { ApiRequestError } from "@/lib/api/client";
import { getAdminPosts } from "@/lib/api/posts";
import type { Paginated, PostSummary } from "@/lib/types";

export default function AdminPostsPage() {
  const router = useRouter();
  const [posts, setPosts] = useState<Paginated<PostSummary> | null>(null);
  const [message, setMessage] = useState<string | null>(null);

  useEffect(() => {
    void getAdminPosts()
      .then(setPosts)
      .catch((error: unknown) => {
        if (error instanceof ApiRequestError && error.status === 401) {
          router.replace("/admin/login");
          return;
        }
        setMessage("記事を取得できませんでした。");
      });
  }, [router]);

  return (
    <section>
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">記事一覧</h1>
        <Link className="rounded bg-slate-900 px-4 py-2 text-white" href="/admin/posts/new">
          新規作成
        </Link>
      </div>
      {message ? (
        <p className="mt-6 text-red-700" role="alert">
          {message}
        </p>
      ) : null}
      {!posts && !message ? <p className="mt-6">読み込み中...</p> : null}
      {posts ? (
        <div className="mt-6 space-y-3">
          {posts.data.map((post) => (
            <article
              className="flex items-center justify-between rounded border border-slate-200 bg-white p-4"
              key={post.id}
            >
              <div>
                <h2 className="font-semibold">{post.title}</h2>
                <p className="text-sm text-slate-500">{post.status}</p>
              </div>
              <Link className="text-sm text-blue-700 hover:underline" href={`/admin/posts/${post.id}/edit`}>
                編集
              </Link>
            </article>
          ))}
          {posts.data.length === 0 ? <p>記事はありません。</p> : null}
        </div>
      ) : null}
    </section>
  );
}
