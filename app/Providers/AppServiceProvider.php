<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
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
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // Check if user has super_admin role without team context
            // We use a direct check here or a specific team check if needed
            return $user->hasRole('super_admin') ? true : null;
        });

        // Mobile clients authenticate with Passport bearer tokens rather than the
        // session cookie used by the web app, so they need their own channel
        // authorization endpoint (POST /api/broadcasting/auth).
        Broadcast::routes([
            'prefix' => 'api',
            'middleware' => ['auth:api'],
        ]);
    }
}
