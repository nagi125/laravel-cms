<?php

namespace App\Providers;

use App\Services\NewsService;
use App\Services\UserService;
use App\Services\UtilityService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->bind(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind('utility', UtilityService::class);
        $this->app->bind('user', UserService::class);
        $this->app->bind('news', NewsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
