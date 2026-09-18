<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The `/analytics` dashboard (fomvasss/laravel-visits, mounted at a renamed
 * path — see config/visits.php) ships with no auth by default — anyone could
 * otherwise browse every visitor's IP, approximate location and device.
 * Gated behind the same admin/super-admin roles that guard the Filament
 * panel, since this site has no other auth system.
 */
class EnsureCanViewAnalyticsDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest('/admin/login');
        }

        abort_unless($user->hasAnyRole(['admin', 'super-admin']), 403);

        return $next($request);
    }
}
