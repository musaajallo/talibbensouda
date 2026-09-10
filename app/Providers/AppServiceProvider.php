<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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

        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Spatie Media Library builds file URLs from filesystems.disks.public.url,
            // which is resolved from APP_URL at config-load time — before forceScheme
            // fires. Re-point it now so uploaded-image URLs are never http:// on an
            // HTTPS page (mixed-content block). Model accessors additionally rebuild
            // media URLs through url(); see App\Support\Media\ResolvesPublicMediaUrl.
            config(['filesystems.disks.public.url' => rtrim(secure_asset('storage'), '/')]);
        }
    }
}
