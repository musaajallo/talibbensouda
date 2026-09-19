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
 *  - the form pages (contact, event registration, testimonial submission) —
 *    a cached page would freeze the honeypot's encrypted timestamp (every
 *    real submission would then fail the timing check) and hide server-side
 *    flash messages;
 *  - health / sitemap endpoints that must reflect live state;
 *  - any request from a signed-in user (an admin previewing the site).
 *
 * Every cache key carries a fingerprint of the current deploy (see
 * useCacheNameSuffix()), so a page cached by one release can never be served by
 * another. Without it, a page cached before a deploy keeps being served
 * afterwards, pointing at hashed CSS/JS files the new build no longer has: 404
 * stylesheets, an unstyled page — only for visitors whose browser hasn't already
 * cached the old file (typically a phone).
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
        'testimonials/submit',
        'health-check',
        'up',
        'sitemap.xml',
    ];

    /**
     * Skip the cache entirely — lookup AND store — for the one request that
     * follows a form submission which flashed something to the session.
     *
     * The site-wide sign-up pop-up POSTs from any page and redirects back, and
     * its thank-you message / validation errors are session flash data. That
     * redirect-back GET would otherwise be answered by a cached copy of the
     * page rendered without the flash (the pop-up then just re-opens empty
     * six seconds later, with no feedback and nothing to say it worked) — and
     * a page rendered *with* the flash must never be stored for everyone else.
     * `enabled()` is the only hook that gates the cache lookup;
     * shouldCacheRequest() only decides what gets stored.
     */
    public function enabled(Request $request): bool
    {
        if ($this->hasPendingFlash($request)) {
            return false;
        }

        return parent::enabled($request);
    }

    protected function hasPendingFlash(Request $request): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        $session = $request->session();

        return $session->has('campaign_signup_success') || $session->has('errors');
    }

    /**
     * Mixed into every cache key by the hasher (on top of the signed-in user id
     * the parent adds), so a release only ever reads and writes its own entries;
     * older ones simply age out with the TTL. See buildFingerprint().
     */
    public function useCacheNameSuffix(Request $request): string
    {
        return parent::useCacheNameSuffix($request).static::buildFingerprint();
    }

    /**
     * Identifies THIS deploy: the release directory plus the asset manifest.
     *
     *  - The release directory (`base_path()` is `dirname(__DIR__)`, a real path)
     *    is unique per zero-downtime release, so every deploy — including one that
     *    only touches Blade or PHP — starts with an empty cache. The deploy script
     *    on the server does NOT clear the response cache, so this is what makes
     *    new markup show immediately instead of after the 24h TTL.
     *  - The manifest hash covers a same-directory deploy that rebuilds assets:
     *    it changes whenever any hashed CSS/JS filename does, which is exactly
     *    what turns a stale cached page into 404 stylesheets.
     *
     * Read fresh each time (a ~3 KB file): a long-lived PHP-FPM worker that
     * outlives a `current` symlink swap must not keep using a stale value.
     */
    public static function buildFingerprint(?string $releasePath = null): string
    {
        $manifest = public_path('build/manifest.json');
        $build = is_file($manifest) ? (string) md5_file($manifest) : 'nobuild';

        return substr(md5(($releasePath ?? base_path()).'|'.$build), 0, 12);
    }

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
