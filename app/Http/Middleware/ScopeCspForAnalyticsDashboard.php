<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The `/analytics` dashboard (fomvasss/laravel-visits, mounted at a renamed
 * path — see config/visits.php) is a third-party, pre-built UI running under
 * the `web` group — a CDN-loaded Tailwind runtime, unpkg (Leaflet) and
 * jsdelivr (Chart.js), plus inline scripts with no nonce. Entirely
 * incompatible with this app's strict nonce-based CSP, same as Filament's
 * admin panel (see AppPreset) — except the panel gets its own middleware
 * stack outside the `web` group entirely, while this dashboard is registered
 * inside it, so it can't be excluded from AddCspHeaders that way.
 *
 * Appended to the `web` group AFTER AddCspHeaders: on the way back out, this
 * runs first (it's the more deeply nested middleware) and sets an explicit,
 * scoped-down policy for the dashboard's own paths. AddCspHeaders then sees a
 * policy already present (its own `hasCspHeader()` escape hatch, intended for
 * exactly this "a middleware further down already set one" case) and leaves
 * it alone instead of overwriting it with the site's default strict policy.
 * The dashboard is auth-gated (EnsureCanViewAnalyticsDashboard) — not worth
 * chasing third-party vendor markup for CSP compliance.
 */
class ScopeCspForAnalyticsDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $dashboardPath = trim((string) config('visits.dashboard.path', 'analytics'), '/');

        if ($request->is($dashboardPath) || $request->is($dashboardPath.'/*')) {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; img-src 'self' https: data:; frame-ancestors 'self'"
            );
        }

        return $response;
    }
}
