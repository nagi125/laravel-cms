import Link from "next/link";
import { getPublicPosts } from "@/lib/api/posts";
import type { Paginated, PostSummary } from "@/lib/types";

export const dynamic = "force-dynamic";

export default async function HomePage() {
  let posts: Paginated<PostSummary> | null = null;

  try {
    posts = await getPublicPosts();
  } catch {
    posts = null;
  }

  if (!posts) {
    return <p role="alert">記事を取得できませんでした。</p>;
  }

  return (
    <section>
      <h1 className="text-3xl font-bold">記事一覧</h1>
      <div className="mt-8 space-y-5">
        {posts.data.length === 0 ? <p>公開済みの記事はありません。</p> : null}
        {posts.data.map((post) => (
          <article className="rounded-lg border border-slate-200 bg-white p-5" key={post.id}>
            <h2 className="text-xl font-semibold">
              <Link href={`/posts/${post.slug}`}>{post.title}</Link>
            </h2>
            {post.excerpt ? <p className="mt-2 text-slate-600">{post.excerpt}</p> : null}
            <p className="mt-3 text-sm text-slate-500">{post.author.name}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
