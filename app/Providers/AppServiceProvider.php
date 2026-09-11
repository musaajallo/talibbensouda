<?php

namespace App\Providers;

use App\Listeners\LogResendDeliveryIssue;
use App\Models\CommunityPhoto;
use App\Models\Event as EventModel;
use App\Models\GalleryPhoto;
use App\Models\GivingProgramme;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Resend\Laravel\Events\EmailBounced;
use Resend\Laravel\Events\EmailComplained;
use Resend\Laravel\Events\EmailDeliveryDelayed;
use Resend\Laravel\Events\EmailFailed;
use Spatie\LaravelSettings\Events\SettingsSaved;
use Spatie\MediaLibrary\MediaCollections\Events\CollectionHasBeenClearedEvent;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Spatie\ResponseCache\Facades\ResponseCache;

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

        $this->flushResponseCacheOnContentChange();

        // Public form endpoints (contact, join, event registration). Generous for
        // a real person, tight enough to stop a spam burst.
        RateLimiter::for('public-forms', fn (Request $request) => [
            Limit::perMinute(5)->by($request->ip()),
            Limit::perDay(40)->by($request->ip()),
        ]);

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

    /**
     * The public site is served from a full-page cache (see
     * App\Support\ResponseCache\CachePublicPages). Flush it whenever the
     * content that feeds those pages changes — a model edited in the panel,
     * an image added, or any settings page saved.
     *
     * A cache-backend hiccup here must never bubble up and fail the save (or
     * the admin page load that triggered it), so the flush is best-effort.
     */
    protected function flushResponseCacheOnContentChange(): void
    {
        $flush = function (): void {
            try {
                ResponseCache::clear();
            } catch (\Throwable $e) {
                Log::warning('Response cache flush failed: '.$e->getMessage());
            }
        };

        $models = [
            EventModel::class,
            Project::class,
            Testimonial::class,
            GalleryPhoto::class,
            CommunityPhoto::class,
            GivingProgramme::class,
        ];

        foreach ($models as $model) {
            /** @var class-string<Model> $model */
            $model::saved($flush);
            $model::deleted($flush);
        }

        Event::listen([
            SettingsSaved::class,
            MediaHasBeenAddedEvent::class,
            CollectionHasBeenClearedEvent::class,
        ], $flush);
    }
}
