"use client";

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import { PostForm } from "@/components/PostForm";
import { ApiRequestError } from "@/lib/api/client";
import { deletePost, getAdminPost, updatePost } from "@/lib/api/posts";
import type { Post } from "@/lib/types";

export default function EditPostPage() {
  const params = useParams<{ id: string }>();
  const router = useRouter();
  const [post, setPost] = useState<Post | null>(null);
  const [message, setMessage] = useState<string | null>(null);
  const [isDeleting, setIsDeleting] = useState(false);

  useEffect(() => {
    void getAdminPost(params.id)
      .then(setPost)
      .catch((error: unknown) => {
        if (error instanceof ApiRequestError && error.status === 401) {
          router.replace("/admin/login");
          return;
        }
        setMessage("記事を取得できませんでした。");
      });
  }, [params.id, router]);

  const handleDelete = async () => {
    if (!window.confirm("この記事を削除しますか？")) {
      return;
    }

    setIsDeleting(true);
    setMessage(null);
    try {
      await deletePost(params.id);
      router.replace("/admin/posts");
    } catch (error) {
      if (error instanceof ApiRequestError && error.status === 401) {
        router.replace("/admin/login");
        return;
      }
      setMessage("記事を削除できませんでした。");
    } finally {
      setIsDeleting(false);
    }
  };

  if (message && !post) {
    return <p role="alert">{message}</p>;
  }

  if (!post) {
    return <p>読み込み中...</p>;
  }

  return (
    <section>
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">記事を編集</h1>
        <button
          className="rounded border border-red-700 px-4 py-2 text-red-700 disabled:opacity-50"
          disabled={isDeleting}
          onClick={handleDelete}
          type="button"
        >
          {isDeleting ? "削除中..." : "削除"}
        </button>
      </div>
      {message ? (
        <p className="mt-4 text-red-700" role="alert">
          {message}
        </p>
      ) : null}
      <PostForm
        initialPost={post}
        onSubmit={(input) => updatePost(params.id, input).then(() => undefined)}
        submitLabel="更新する"
      />
    </section>
  );
}
