"use client";

import { useRouter } from "next/navigation";
import { PostForm } from "@/components/PostForm";
import { createPost } from "@/lib/api/posts";

export default function NewPostPage() {
  const router = useRouter();

  return (
    <section>
      <h1 className="text-3xl font-bold">記事を作成</h1>
      <PostForm
        onSubmit={async (input) => {
          const post = await createPost(input);
          router.replace(`/admin/posts/${post.id}/edit`);
        }}
        submitLabel="作成する"
      />
    </section>
  );
}
