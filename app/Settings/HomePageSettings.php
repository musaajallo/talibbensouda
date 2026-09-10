<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

/**
 * Editable copy for the home page (/). Repeating content with images —
 * projects, community photos, recognition — lives in its own models; this
 * class holds the section copy and the hero.
 */
class HomePageSettings extends Settings
{
    // Hero
    public string $hero_eyebrow = '';

    public string $hero_headline = '';

    /** Substring of the headline that gets the italic accent. */
    public string $hero_emphasis = '';

    public string $hero_lead = '';

    public string $hero_primary_label = '';

    public string $hero_primary_url = '';

    public string $hero_secondary_label = '';

    public string $hero_secondary_url = '';

    /** Repeater rows: ['image' => disk path|null, 'bg' => hex, 'position' => css]. */
    public array $hero_slides = [];

    // Stats bar — repeater rows: ['value' => '38', 'suffix' => 'km', 'label' => '...'].
    public array $stats = [];

    // About teaser
    public string $about_eyebrow = '';

    public string $about_headline = '';

    public string $about_body = '';

    public string $about_cta_label = '';

    // Featured video
    public string $video_eyebrow = '';

    public string $video_headline = '';

    public string $video_body = '';

    public string $video_quote = '';

    /** YouTube video id (the part after v=). Empty shows the "coming soon" placeholder. */
    public string $video_youtube_id = '';

    // Section headers
    public string $projects_eyebrow = '';

    public string $projects_headline = '';

    public string $projects_lead = '';

    public string $projects_cta_label = '';

    public string $community_eyebrow = '';

    public string $community_headline = '';

    public string $community_lead = '';

    public string $recognition_eyebrow = '';

    public string $recognition_headline = '';

    public string $recognition_lead = '';

    public string $milestones_eyebrow = '';

    public string $milestones_headline = '';

    public string $milestones_lead = '';

    public string $milestones_cta_label = '';

    // CTA banner
    public string $cta_headline = '';

    public string $cta_lead = '';

    public string $cta_primary_label = '';

    public string $cta_primary_url = '';

    public string $cta_secondary_label = '';

    public string $cta_secondary_url = '';

    public static function group(): string
    {
        return 'home_page';
    }

    /**
     * Hero headline as HTML: newline → <br>, the emphasis substring wrapped in <em>.
     */
    public function headlineHtml(): string
    {
        $html = nl2br(e($this->hero_headline));

        if ($this->hero_emphasis !== '') {
            $html = str_replace(e($this->hero_emphasis), '<em>'.e($this->hero_emphasis).'</em>', $html);
        }

        return $html;
    }

    /**
     * Configured hero slides with resolved image URLs, or a default set built
     * from the bundled hero images when none are configured.
     *
     * @return array<int, array{img: string, bg: string, pos: string}>
     */
    public function heroSlides(): array
    {
        $configured = collect($this->hero_slides)
            ->filter(fn ($slide) => filled($slide['image'] ?? null))
            ->map(fn ($slide) => [
                'img' => Storage::disk('public')->url($slide['image']),
                'bg' => $slide['bg'] ?? '#0d1b38',
                'pos' => $slide['position'] ?? 'center',
            ])
            ->values()
            ->all();

        if ($configured !== []) {
            return $configured;
        }

        return [
            ['img' => asset('images/hero-talib-desk.webp'), 'bg' => '#0d1b38', 'pos' => 'center'],
            ['img' => asset('images/hero-masquerade.webp'), 'bg' => '#0a1525', 'pos' => 'center'],
            ['img' => asset('images/hero-supporters.webp'), 'bg' => '#112044', 'pos' => 'center right'],
            ['img' => asset('images/hero-hall.webp'), 'bg' => '#091422', 'pos' => 'center'],
            ['img' => asset('images/hero-victory.webp'), 'bg' => '#0d1b38', 'pos' => 'center right'],
        ];
    }
}
