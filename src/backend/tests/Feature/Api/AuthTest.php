<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

    public function test_csrf_cookie_endpoint_returns_xsrf_token_cookie(): void
    {
        $this->get('/sanctum/csrf-cookie')
            ->assertNoContent()
            ->assertCookie('XSRF-TOKEN');
    }

    public function test_login_requires_valid_csrf_token_for_stateful_requests(): void
    {
        $originalEnvironment = $this->app['env'];
        $this->app['env'] = 'local';

        try {
            $user = User::factory()->create(['password' => 'password']);

            $this->withHeader('Origin', 'http://localhost:3000')
                ->postJson('/api/auth/login', [
                    'email' => $user->email,
                    'password' => 'password',
                ])
                ->assertStatus(419);

            $csrfResponse = $this->get('/sanctum/csrf-cookie');
            $sessionCookie = $csrfResponse->getCookie((string) config('session.cookie'));
            $xsrfCookie = $csrfResponse->getCookie('XSRF-TOKEN', decrypt: false);

            $this->assertNotNull($sessionCookie);
            $this->assertNotNull($xsrfCookie);

            $this->withCookie((string) config('session.cookie'), $sessionCookie->getValue());
            $this->withUnencryptedCookies(['XSRF-TOKEN' => $xsrfCookie->getValue()]);
            $this->withHeader('X-XSRF-TOKEN', urldecode($xsrfCookie->getValue()))
                ->postJson('/api/auth/login', [
                    'email' => $user->email,
                    'password' => 'password',
                ])
                ->assertOk();
        } finally {
            $this->app['env'] = $originalEnvironment;
        }
    }

    public function test_login_preflight_allows_frontend_origin_with_credentials(): void
    {
        $this->options('/api/auth/login', [], [
            'Origin' => 'http://localhost:3000',
            'Access-Control-Request-Method' => 'POST',
        ])
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function test_login_preflight_does_not_echo_unknown_origin(): void
    {
        $response = $this->options('/api/auth/login', [], [
            'Origin' => 'http://evil.example.com',
            'Access-Control-Request-Method' => 'POST',
        ]);

        $response
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000');

        $this->assertNotSame('http://evil.example.com', $response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_user_can_log_out_from_stateful_session(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $loginResponse = $this->withHeader('Origin', 'http://localhost:3000')
            ->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $loginResponse->assertOk();
        $sessionCookie = $loginResponse->getCookie((string) config('session.cookie'));

        $this->assertNotNull($sessionCookie);
        $this->withCookie((string) config('session.cookie'), $sessionCookie->getValue());

        $this->getJson('/api/auth/me')->assertOk();

        $logoutResponse = $this->postJson('/api/auth/logout');

        $logoutResponse->assertNoContent();
        Auth::forgetGuards();
        $this->assertGuest();

        $logoutCookie = $logoutResponse->getCookie((string) config('session.cookie'));

        $this->assertNotNull($logoutCookie);
        $this->withCookie((string) config('session.cookie'), $logoutCookie->getValue());

        $this->getJson('/api/auth/me')->assertUnauthorized();
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
