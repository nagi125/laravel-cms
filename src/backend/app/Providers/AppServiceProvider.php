<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const LOGIN_RATE_LIMIT = 5;

    public const API_RATE_LIMIT = 60;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(self::LOGIN_RATE_LIMIT)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip(),
            );
        });

        RateLimiter::for('api', function (Request $request): Limit {
            return Limit::perMinute(self::API_RATE_LIMIT)->by(
                (string) ($request->user()?->getAuthIdentifier() ?? $request->ip()),
            );
        });
    }
}
