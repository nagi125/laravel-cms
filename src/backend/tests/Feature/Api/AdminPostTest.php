<?php

namespace Tests\Feature\Api;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $attributes = $this->postAttributes();

        $response = $this->actingAs($user)->postJson('/api/admin/posts', $attributes);

        $response
            ->assertCreated()
            ->assertJsonStructure(['data' => [
                'id', 'title', 'slug', 'excerpt', 'body', 'status', 'published_at', 'created_at', 'updated_at',
                'author' => ['id', 'name'],
            ]])
            ->assertJsonPath('data.slug', $attributes['slug'])
            ->assertJsonPath('data.status', PostStatus::Draft->value);

        $this->assertDatabaseHas('posts', ['slug' => $attributes['slug'], 'user_id' => $user->id]);
    }

    public function test_published_post_without_publish_date_is_immediately_public(): void
    {
        $user = User::factory()->create();
        $attributes = $this->postAttributes(slug: 'immediately-public', status: PostStatus::Published->value);

        $response = $this->actingAs($user)->postJson('/api/admin/posts', $attributes);

        $response
            ->assertCreated()
            ->assertJsonStructure(['data' => ['published_at']]);

        $this->assertNotNull($response->json('data.published_at'));

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonFragment(['slug' => $attributes['slug']]);
    }

    public function test_future_publish_date_is_preserved_and_not_public_yet(): void
    {
        $user = User::factory()->create();
        $futurePublishedAt = now()->addDay()->startOfSecond()->toISOString();
        $attributes = $this->postAttributes(
            slug: 'scheduled-post',
            status: PostStatus::Published->value,
            publishedAt: $futurePublishedAt,
        );

        $response = $this->actingAs($user)->postJson('/api/admin/posts', $attributes);

        $response->assertCreated();
        $this->assertSame($futurePublishedAt, Carbon::parse($response->json('data.published_at'))->toISOString());

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonMissing(['slug' => $attributes['slug']]);
    }

    public function test_draft_post_does_not_receive_publish_date(): void
    {
        $user = User::factory()->create();
        $attributes = $this->postAttributes(slug: 'draft-without-date');

        $this->actingAs($user)
            ->postJson('/api/admin/posts', $attributes)
            ->assertCreated()
            ->assertJsonPath('data.published_at', null);
    }

    public function test_updating_post_to_published_without_date_sets_publish_date(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $attributes = $this->postAttributes(
            slug: $post->slug,
            status: PostStatus::Published->value,
        );

        $response = $this->actingAs($user)->putJson('/api/admin/posts/'.$post->id, $attributes);

        $response->assertOk();
        $this->assertNotNull($response->json('data.published_at'));
    }

    public function test_authenticated_user_can_filter_post_list_by_status(): void
    {
        $user = User::factory()->create();
        $draftPost = Post::factory()->for($user)->create();
        Post::factory()->for($user)->published()->create();

        $this->actingAs($user)
            ->getJson('/api/admin/posts?status='.PostStatus::Draft->value)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'updated_at', 'author' => ['id', 'name']]],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
            ])
            ->assertJsonPath('data.0.slug', $draftPost->slug);
    }

    public function test_authenticated_user_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $attributes = $this->postAttributes(slug: 'updated-post');

        $this->actingAs($user)
            ->putJson('/api/admin/posts/'.$post->id, $attributes)
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'title', 'slug', 'body', 'author' => ['id', 'name']]])
            ->assertJsonPath('data.slug', $attributes['slug']);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'slug' => $attributes['slug']]);
    }

    public function test_authenticated_user_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson('/api/admin/posts/'.$post->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_guests_cannot_manage_posts(): void
    {
        $post = Post::factory()->create();

        $this->postJson('/api/admin/posts', $this->postAttributes())->assertUnauthorized();
        $this->putJson('/api/admin/posts/'.$post->id, $this->postAttributes(slug: 'guest-update'))->assertUnauthorized();
        $this->deleteJson('/api/admin/posts/'.$post->id)->assertUnauthorized();
    }

    public function test_post_validation_failure_returns_unprocessable_response(): void
    {
        $user = User::factory()->create();
        $attributes = $this->postAttributes();
        unset($attributes['body']);

        $this->actingAs($user)
            ->postJson('/api/admin/posts', $attributes)
            ->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['body']]);
    }

    public function test_duplicate_slug_returns_unprocessable_response(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['slug' => 'duplicate-slug']);

        $this->actingAs($user)
            ->postJson('/api/admin/posts', $this->postAttributes(slug: $post->slug))
            ->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['slug']]);
    }

    public function test_non_numeric_and_missing_post_ids_return_not_found(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->getJson('/api/admin/posts/abc')->assertNotFound();
        $this->actingAs($user)->getJson('/api/admin/posts/999999')->assertNotFound();
    }

    /** @return array<string, string|null> */
    private function postAttributes(
        string $slug = 'new-post',
        ?string $status = null,
        ?string $publishedAt = null,
    ): array {
        return [
            'title' => 'New Post',
            'slug' => $slug,
            'excerpt' => 'A short excerpt.',
            'body' => 'Post body.',
            'status' => $status ?? PostStatus::Draft->value,
            'published_at' => $publishedAt,
        ];
    }
}
