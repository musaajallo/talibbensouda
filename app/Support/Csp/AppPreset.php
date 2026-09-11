<?php

namespace App\Support\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

/**
 * App-level CSP additions on top of Spatie's Basic preset.
 *
 * The Basic preset already covers self-served scripts/styles/images.
 * Add domains here that this app legitimately loads from (analytics,
 * fonts, embeds, etc.) so the browser stops blocking them.
 *
 * Vite HMR is handled separately via config('csp.enabled_while_hot_reloading').
 */
class AppPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            // Allow this app to be embedded in iframes only by itself.
            ->add(Directive::FRAME_ANCESTORS, Keyword::SELF)

            // Block plugins (Flash etc).
            ->add(Directive::OBJECT, Keyword::NONE)

            // Force the browser to upgrade http:// asset URLs to https:// in production.
            ->add(Directive::UPGRADE_INSECURE_REQUESTS, [])

            // Fonts are self-hosted (public/fonts + resources/sass/shared/base/_fonts.scss),
            // so no external font/style origins are needed.

            // YouTube embed on the home page's "featured video" section
            // (only rendered when an editor sets a video ID).
            ->add(Directive::FRAME, 'https://www.youtube-nocookie.com')
            ->add(Directive::FRAME, 'https://www.youtube.com');

        // Cookieless analytics — only opened up when a domain is configured
        // (see config/services.php + resources/views/partials/analytics.blade.php).
        if ($src = config('services.analytics.domain') ? config('services.analytics.src') : null) {
            $origin = parse_url($src, PHP_URL_SCHEME).'://'.parse_url($src, PHP_URL_HOST);

            $policy
                ->add(Directive::SCRIPT, $origin)
                ->add(Directive::CONNECT, $origin);
        }

        // Examples — uncomment as you add integrations:
        //
        // Bunny Fonts (privacy-friendly Google Fonts mirror)
        // $policy->add(Directive::STYLE, 'https://fonts.bunny.net');
        // $policy->add(Directive::FONT,  'https://fonts.bunny.net');
        //
        // Plausible / Fathom analytics
        // $policy->add(Directive::SCRIPT,  'https://plausible.io');
        // $policy->add(Directive::CONNECT, 'https://plausible.io');
        //
        // Stripe
        // $policy->add(Directive::SCRIPT,  'https://js.stripe.com');
        // $policy->add(Directive::FRAME,   'https://js.stripe.com');
        // $policy->add(Directive::CONNECT, 'https://api.stripe.com');
    }
}
