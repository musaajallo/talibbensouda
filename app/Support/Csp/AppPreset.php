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

            // Google Fonts, loaded in resources/views/layouts/app.blade.php.
            ->add(Directive::STYLE, 'https://fonts.googleapis.com')
            ->add(Directive::FONT, 'https://fonts.gstatic.com');

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
