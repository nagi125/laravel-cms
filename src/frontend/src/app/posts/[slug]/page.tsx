import { notFound } from "next/navigation";
import { ApiRequestError } from "@/lib/api/client";
import { getPublicPost } from "@/lib/api/posts";
import type { Post } from "@/lib/types";

export const dynamic = "force-dynamic";

type PostPageProps = {
  params: Promise<{ slug: string }>;
};

export default async function PostPage({ params }: PostPageProps) {
  const { slug } = await params;
  let post: Post | null = null;
  let isNotFound = false;

  try {
    post = await getPublicPost(slug);
  } catch (error) {
    if (error instanceof ApiRequestError && error.status === 404) {
      isNotFound = true;
    }
  }

  if (isNotFound) {
    notFound();
  }

  if (!post) {
    return <p role="alert">記事を取得できませんでした。</p>;
  }

  return (
    <article>
      <p className="text-sm text-slate-500">{post.author.name}</p>
      <h1 className="mt-2 text-3xl font-bold">{post.title}</h1>
      {post.excerpt ? <p className="mt-4 text-lg text-slate-600">{post.excerpt}</p> : null}
      <div className="mt-8 whitespace-pre-wrap leading-8">{post.body}</div>
    </article>
  );
}
