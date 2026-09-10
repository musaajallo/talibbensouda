<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Header / footer chrome that isn't page content: the "Join Party" call to
 * action and the footer identity lines.
 */
class SiteChromeSettings extends Settings
{
    public string $join_party_label = 'Join Party';

    public string $join_party_url = 'https://unitemovementgambia.com/join';

    public string $footer_tagline = "The People's Mayor.";

    public string $copyright_name = 'Talib Bensouda';

    public static function group(): string
    {
        return 'site_chrome';
    }
}
