<?php

namespace App\Support\Analytics;

use Fomvasss\Visits\Contracts\ConsentResolverInterface;
use Illuminate\Http\Request;

/**
 * The cookie-consent banner (layouts/app.blade.php) stores the visitor's
 * choice in localStorage, which the server can't read. Its `save()` method
 * also mirrors the analytics choice into a plain `tb_analytics_consent`
 * cookie (1/0) for exactly this reason — read here so TrackVisit only tracks
 * once analytics consent has actually been given, not before.
 */
class CookieConsentResolver implements ConsentResolverInterface
{
    public function hasConsent(Request $request): bool
    {
        return $request->cookie('tb_analytics_consent') === '1';
    }
}
