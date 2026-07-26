export type PostStatus = "draft" | "published";

export type User = {
  id: number;
  name: string;
  email: string;
};

export type PostAuthor = {
  id: number;
  name: string;
};

export type PostSummary = {
  id: number;
  title: string;
  slug: string;
  excerpt: string | null;
  status: PostStatus;
  published_at: string | null;
  created_at: string;
  updated_at: string;
  author: PostAuthor;
};

export type Post = PostSummary & {
  body: string;
};

export type Paginated<T> = {
  data: T[];
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };
};

export type ApiError = {
  message: string;
  errors?: Record<string, string[]>;
};
