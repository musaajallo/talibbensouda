<?php

namespace App\Settings;

use App\Settings\Concerns\SplitsParagraphs;
use Spatie\LaravelSettings\Settings;

/**
 * Editable copy for /giving-back. Programme cards are the GivingProgramme
 * model; the photo grid is CommunityPhoto (group "community-support").
 */
class GivingBackPageSettings extends Settings
{
    use SplitsParagraphs;

    public string $hero_eyebrow = '';

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public string $intro_eyebrow = '';

    public string $intro_headline = '';

    public string $intro_body = '';

    public string $intro_quote = '';

    public string $intro_quote_attribution = '';

    /** Repeater rows: ['value' => 'D200k', 'label' => '...']. */
    public array $intro_stats = [];

    public string $programmes_eyebrow = '';

    public string $programmes_headline = '';

    public string $programmes_lead = '';

    /** Repeater rows: ['value' => '19', 'suffix' => '', 'label' => '...']. */
    public array $impact_stats = [];

    public string $community_eyebrow = '';

    public string $community_headline = '';

    public string $community_lead = '';

    public string $enterprises_eyebrow = '';

    public string $enterprises_headline = '';

    public string $enterprises_lead = '';

    /** Repeater rows: ['title' => '', 'body' => '', 'role' => '']. */
    public array $enterprises = [];

    public string $help_eyebrow = '';

    public string $help_headline = '';

    public string $help_lead = '';

    /** Repeater rows: ['title' => '', 'description' => '', 'cta_label' => '', 'cta_url' => '']. */
    public array $help_cards = [];

    public string $cta_headline = '';

    public string $cta_lead = '';

    public string $cta_primary_label = '';

    public string $cta_primary_url = '';

    public string $cta_secondary_label = '';

    public string $cta_secondary_url = '';

    public static function group(): string
    {
        return 'giving_back_page';
    }
}
