<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(fn ($user, string $ability) => $user->hasRole('super-admin') ? true : null);

        // Allow admins to view the Pulse dashboard at /pulse.
        Gate::define('viewPulse', fn ($user) => $user->hasAnyRole(['admin', 'super-admin']));
    }
}
