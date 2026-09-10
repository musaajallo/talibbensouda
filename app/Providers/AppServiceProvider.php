<?php

namespace App\Providers;

use App\Listeners\LogResendDeliveryIssue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Resend\Laravel\Events\EmailBounced;
use Resend\Laravel\Events\EmailComplained;
use Resend\Laravel\Events\EmailDeliveryDelayed;
use Resend\Laravel\Events\EmailFailed;

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

        // Surface outbound-mail problems reported by Resend (POST /resend/webhook).
        Event::listen(
            [EmailBounced::class, EmailComplained::class, EmailFailed::class, EmailDeliveryDelayed::class],
            LogResendDeliveryIssue::class,
        );

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
