<?php

namespace App\Providers;

use App\Auth\TokenGuard;
use App\Models\Api;
use App\Models\Server;
use App\Policies\ApiPolicy;
use App\Policies\ServerPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Paginator::useBootstrap();

        Gate::policy(Server::class, ServerPolicy::class);
        Gate::policy(Api::class, ApiPolicy::class);

        Auth::extend('token', function ($app, $name, array $config) {
            $guard = new TokenGuard(
                Auth::createUserProvider($config['provider']),
                $app['request'],
                $config['input_key'] ?? 'api_token',
                $config['storage_key'] ?? 'api_token',
                $config['hash'] ?? false
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
