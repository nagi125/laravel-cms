<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@example.com';

    private const PUBLISHED_POST_COUNT = 8;

    private const DRAFT_POST_COUNT = 2;

    public function run(): void
    {
        $user = User::query()->where('email', self::ADMIN_EMAIL)->firstOrFail();

        foreach (range(1, self::PUBLISHED_POST_COUNT) as $number) {
            $this->upsertPost($user, $number, PostStatus::Published);
        }

        foreach (range(1, self::DRAFT_POST_COUNT) as $number) {
            $this->upsertPost($user, $number, PostStatus::Draft);
        }
    }

    private function upsertPost(User $user, int $number, PostStatus $status): void
    {
        $prefix = $status->value.'-post';

        Post::query()->updateOrCreate(
            ['slug' => $prefix.'-'.$number],
            [
                'user_id' => $user->id,
                'title' => ucfirst($status->value).' Post '.$number,
                'excerpt' => 'Excerpt for '.$prefix.' '.$number,
                'body' => 'Body for '.$prefix.' '.$number,
                'status' => $status,
                'published_at' => $status === PostStatus::Published ? now()->subDays($number) : null,
            ],
        );
    }
}
