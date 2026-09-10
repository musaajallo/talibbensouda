<?php

namespace App\Support\ResponseCache;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

/**
 * Full-page cache for the public marketing site only.
 *
 * Everything the front controller renders for an anonymous visitor is a pure
 * function of the CMS content, so it is safe to serve from a file cache and
 * flush on any content edit (see AppServiceProvider). Excluded:
 *
 *  - the Filament panel and its Livewire/upload traffic (auth-gated, dynamic);
 *  - the two form pages — a cached page would freeze the honeypot's encrypted
 *    timestamp (every real submission would then fail the timing check) and
 *    hide server-side flash messages;
 *  - health / sitemap / feed endpoints that must reflect live state;
 *  - any request from a signed-in user (an admin previewing the site).
 *
 * The CSRF token and CSP nonce are NOT a problem here: CsrfTokenReplacer swaps
 * the token per request, and CacheResponse sits outside AddCspHeaders in the
 * middleware stack so a hit replays the nonce that is baked into the cached
 * HTML instead of getting a fresh, mismatched one.
 */
class CachePublicPages extends CacheAllSuccessfulGetRequests
{
    /**
     * Path prefixes that must always reach the application.
     *
     * @var list<string>
     */
    protected array $except = [
        'admin',
        'livewire',
        'resend',
        'contact',
        'events/register',
        'health-check',
        'up',
        'sitemap.xml',
        'feed',
    ];

    public function shouldCacheRequest(Request $request): bool
    {
        if (Auth::check()) {
            return false;
        }

        if ($this->isExcludedPath($request->path())) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }

    protected function isExcludedPath(string $path): bool
    {
        $path = ltrim($path, '/');

        foreach ($this->except as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }
}
