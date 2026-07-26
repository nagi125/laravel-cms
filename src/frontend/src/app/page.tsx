import Link from "next/link";
import { getPublicPosts } from "@/lib/api/posts";
import type { Paginated, PostSummary } from "@/lib/types";

export const dynamic = "force-dynamic";

const FIRST_PAGE = 1;

type HomePageProps = {
  searchParams: Promise<{ page?: string | string[] }>;
};

export default async function HomePage({ searchParams }: HomePageProps) {
  const query = await searchParams;
  const pageParameter = query.page;
  const requestedPage = typeof pageParameter === "string" ? Number(pageParameter) : FIRST_PAGE;
  const page = Number.isInteger(requestedPage) && requestedPage >= FIRST_PAGE ? requestedPage : FIRST_PAGE;
  let posts: Paginated<PostSummary> | null = null;

  try {
    posts = await getPublicPosts(page);
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
      <nav aria-label="記事一覧のページ送り" className="mt-8 flex items-center justify-between">
        {posts.meta.current_page > FIRST_PAGE ? (
          <Link className="text-sm text-blue-700 hover:underline" href={`/?page=${posts.meta.current_page - 1}`}>
            前へ
          </Link>
        ) : (
          <span aria-disabled="true" className="text-sm text-slate-400">
            前へ
          </span>
        )}
        <span className="text-sm text-slate-600">
          {posts.meta.current_page} / {posts.meta.last_page}
        </span>
        {posts.meta.current_page < posts.meta.last_page ? (
          <Link className="text-sm text-blue-700 hover:underline" href={`/?page=${posts.meta.current_page + 1}`}>
            次へ
          </Link>
        ) : (
          <span aria-disabled="true" className="text-sm text-slate-400">
            次へ
          </span>
        )}
      </nav>
    </section>
  );
}
