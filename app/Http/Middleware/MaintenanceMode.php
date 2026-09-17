<?php

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A panel-toggleable "coming soon" gate for the public site, separate from
 * Laravel's own `artisan down`. Registered ahead of CacheResponse in the
 * `web` group (bootstrap/app.php) so a cached page from before maintenance
 * mode was turned on can never be served instead of the gate.
 */
class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = app(GeneralSettings::class);

        if (! $settings->maintenance_mode) {
            return $next($request);
        }

        if (
            $request->user()
            || $request->is('admin', 'admin/*')
            || $request->routeIs('filament.admin.*')
            || $request->is('health-check', 'up', 'sitemap.xml')
        ) {
            return $next($request);
        }

        return response()
            ->view('maintenance', status: 503)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Retry-After', '3600')
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }
}
