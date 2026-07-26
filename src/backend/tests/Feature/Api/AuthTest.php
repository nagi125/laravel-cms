<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_with_stateful_origin(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeader('Origin', 'http://localhost:3000')->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJsonPath('data.email', $user->email);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $this->withHeader('Origin', 'http://localhost:3000')
            ->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'invalid-password',
            ])
            ->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['email']]);
    }

    public function test_login_is_rate_limited_after_maximum_failed_attempts(): void
    {
        $email = 'rate-limited@example.com';
        $ipAddress = '127.0.0.1';

        $this->withServerVariables(['REMOTE_ADDR' => $ipAddress]);

        try {
            for ($attempt = 0; $attempt < AppServiceProvider::LOGIN_RATE_LIMIT; $attempt++) {
                $this->withHeader('Origin', 'http://localhost:3000')
                    ->postJson('/api/auth/login', [
                        'email' => $email,
                        'password' => 'invalid-password',
                    ])
                    ->assertUnprocessable();
            }

            $this->withHeader('Origin', 'http://localhost:3000')
                ->postJson('/api/auth/login', [
                    'email' => $email,
                    'password' => 'invalid-password',
                ])
                ->assertTooManyRequests();
        } finally {
            RateLimiter::clear(md5('login'.strtolower($email).'|'.$ipAddress));
            RateLimiter::clear(md5('api'.$ipAddress));
        }
    }

    public function test_guest_cannot_view_current_user(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized();
    }

    public function test_authenticated_user_can_view_current_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJsonPath('data.id', $user->id);
    }
}
