<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    public const DEFAULT_PER_PAGE = 15;

    public const MAX_PER_PAGE = 100;

    public function __construct(private readonly PostRepositoryInterface $postRepository) {}

    public function paginatePublished(mixed $perPage): LengthAwarePaginator
    {
        return $this->postRepository->paginatePublished($this->resolvePerPage($perPage));
    }

    public function findPublishedBySlug(string $slug): ?Post
    {
        return $this->postRepository->findPublishedBySlug($slug);
    }

    public function paginateAdmin(?string $status, mixed $perPage): LengthAwarePaginator
    {
        return $this->postRepository->paginateAdmin(
            PostStatus::tryFrom($status ?? ''),
            $this->resolvePerPage($perPage),
        );
    }

    public function findById(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    /** @param array<string, mixed> $attributes */
    public function create(User $user, array $attributes): Post
    {
        return $this->postRepository->create($user, $this->applyDefaultPublishedAt($attributes));
    }

    /** @param array<string, mixed> $attributes */
    public function update(Post $post, array $attributes): Post
    {
        return $this->postRepository->update($post, $this->applyDefaultPublishedAt($attributes));
    }

    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    public function resolvePerPage(mixed $perPage): int
    {
        $validatedPerPage = filter_var($perPage, FILTER_VALIDATE_INT);

        if ($validatedPerPage === false || $validatedPerPage < 1 || $validatedPerPage > self::MAX_PER_PAGE) {
            return self::DEFAULT_PER_PAGE;
        }

        return $validatedPerPage;
    }

    /** @param array<string, mixed> $attributes */
    private function applyDefaultPublishedAt(array $attributes): array
    {
        $isPublished = ($attributes['status'] ?? null) === PostStatus::Published
            || ($attributes['status'] ?? null) === PostStatus::Published->value;

        if (! $isPublished || ($attributes['published_at'] ?? null) !== null) {
            return $attributes;
        }

        $attributes['published_at'] = now();

        return $attributes;
    }
}
