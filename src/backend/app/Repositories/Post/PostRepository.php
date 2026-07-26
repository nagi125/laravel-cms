<?php

namespace App\Repositories\Post;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function paginatePublished(int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->with('user')
            ->where('status', PostStatus::Published->value)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Post
    {
        return Post::query()
            ->with('user')
            ->where('slug', $slug)
            ->where('status', PostStatus::Published->value)
            ->where('published_at', '<=', now())
            ->first();
    }

    public function paginateAdmin(?PostStatus $status, int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->with('user')
            ->when($status, fn ($query) => $query->where('status', $status->value))
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Post
    {
        return Post::query()->with('user')->find($id);
    }

    public function create(User $user, array $attributes): Post
    {
        $post = Post::query()->create(array_merge($attributes, ['user_id' => $user->id]));

        return $post->load('user');
    }

    public function update(Post $post, array $attributes): Post
    {
        $post->update($attributes);

        return $post->refresh()->load('user');
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
