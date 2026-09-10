<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editable copy for /peoples-mayor. The project showcase and the community
 * photo grid are backed by the Project and CommunityPhoto models.
 */
class PeoplesMayorPageSettings extends Settings
{
    public string $hero_eyebrow = '';

    public string $hero_title = '';

    public string $hero_subtitle = '';

    /** Repeater rows: ['value' => '38', 'suffix' => 'km', 'label' => '...']. */
    public array $impact_stats = [];

    public string $pull_quote = '';

    public string $pull_quote_attribution = '';

    public string $community_eyebrow = '';

    public string $community_headline = '';

    public string $community_lead = '';

    public string $figures_note = '';

    public string $cta_headline = '';

    public string $cta_lead = '';

    public string $cta_primary_label = '';

    public string $cta_primary_url = '';

    public string $cta_secondary_label = '';

    public string $cta_secondary_url = '';

    public static function group(): string
    {
        return 'peoples_mayor_page';
    }
}
