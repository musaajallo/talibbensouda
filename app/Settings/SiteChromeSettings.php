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

    public bool $election_countdown_enabled = true;

    public string $election_countdown_label = 'To the 2026 Presidential Election';

    /** Date only (Y-m-d) — treated as UTC midnight, same as the events ICS export. */
    public string $election_date = '2026-12-04';

    // Site-wide sign-up pop-up (resources/views/components/campaign-signup-popup.blade.php)
    public bool $signup_popup_enabled = true;

    public string $signup_popup_heading = "Join Talib's Campaign";

    public string $signup_popup_body = "We can win this, but we need your help. Leave your details and we'll keep you posted on how to get involved.";

    public string $signup_popup_button_label = "I'm In";

    public static function group(): string
    {
        return 'site_chrome';
    }
}
