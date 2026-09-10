<?php

namespace App\Settings;

use App\Settings\Concerns\SplitsParagraphs;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

/**
 * Editable copy for /about.
 */
class AboutPageSettings extends Settings
{
    use SplitsParagraphs;

    public string $hero_eyebrow = '';

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public string $bio_eyebrow = '';

    public string $bio_headline = '';

    public string $bio_body = '';

    public ?string $bio_photo = null;

    public string $timeline_eyebrow = '';

    public string $timeline_headline = '';

    public string $timeline_lead = '';

    /** Repeater rows: ['year' => '2018', 'title' => '', 'description' => '']. */
    public array $timeline = [];

    public string $values_eyebrow = '';

    public string $values_headline = '';

    public string $values_lead = '';

    /** Repeater rows: ['title' => '', 'description' => '']. */
    public array $values = [];

    public string $national_eyebrow = '';

    public string $national_headline = '';

    public string $national_body = '';

    /** Flat list of pillar strings (Filament "simple" repeater). */
    public array $national_pillars = [];

    public ?string $national_logo = null;

    public string $national_logo_name = '';

    public string $national_logo_caption = '';

    public string $national_cta_label = '';

    public string $national_cta_url = '';

    public string $cta_headline = '';

    public string $cta_lead = '';

    public string $cta_primary_label = '';

    public string $cta_primary_url = '';

    public string $cta_secondary_label = '';

    public string $cta_secondary_url = '';

    public static function group(): string
    {
        return 'about_page';
    }

    /** Absolute URL for the biography portrait, falling back to the bundled photo. */
    public function bioPhotoUrl(): string
    {
        return $this->bio_photo && Storage::disk('public')->exists($this->bio_photo)
            ? Storage::disk('public')->url($this->bio_photo)
            : asset('images/about-talib.webp');
    }

    public function nationalLogoUrl(): ?string
    {
        return $this->national_logo && Storage::disk('public')->exists($this->national_logo)
            ? Storage::disk('public')->url($this->national_logo)
            : null;
    }
}
