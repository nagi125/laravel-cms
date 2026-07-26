<?php

namespace App\Repositories\Post;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function paginatePublished(int $perPage): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?Post;

    public function paginateAdmin(?PostStatus $status, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?Post;

    /** @param array<string, mixed> $attributes */
    public function create(User $user, array $attributes): Post;

    /** @param array<string, mixed> $attributes */
    public function update(Post $post, array $attributes): Post;

    public function delete(Post $post): void;
}
