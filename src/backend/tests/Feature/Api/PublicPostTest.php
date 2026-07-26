<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_ok_status(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertJsonPath('status', 'ok');
    }

    public function test_public_index_does_not_include_drafts(): void
    {
        $user = User::factory()->create();
        $publishedPost = Post::factory()->for($user)->published()->create();
        $draftPost = Post::factory()->for($user)->create();

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'updated_at', 'author' => ['id', 'name']]],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
            ])
            ->assertJsonPath('data.0.slug', $publishedPost->slug)
            ->assertJsonMissing(['slug' => $draftPost->slug]);
    }

    public function test_published_post_can_be_viewed(): void
    {
        $post = Post::factory()->published()->create();

        $this->getJson('/api/posts/'.$post->slug)
            ->assertOk()
            ->assertJsonStructure(['data' => [
                'id', 'title', 'slug', 'excerpt', 'body', 'status', 'published_at', 'created_at', 'updated_at',
                'author' => ['id', 'name'],
            ]])
            ->assertJsonPath('data.slug', $post->slug);
    }

    public function test_draft_post_returns_not_found(): void
    {
        $post = Post::factory()->create();

        $this->getJson('/api/posts/'.$post->slug)->assertNotFound();
    }

    public function test_invalid_per_page_uses_default_value(): void
    {
        Post::factory()->published()->create();

        $this->getJson('/api/posts?per_page='.(PostService::MAX_PER_PAGE + 1))
            ->assertOk()
            ->assertJsonPath('meta.per_page', PostService::DEFAULT_PER_PAGE);
    }
}
