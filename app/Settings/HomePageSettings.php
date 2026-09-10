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

    /** Path on the `public` disk to the about-teaser portrait, or null for the bundled photo. */
    public ?string $about_image = null;

    // Featured video
    public string $video_eyebrow = '';

    public string $video_headline = '';

    public string $video_body = '';

    public string $video_quote = '';

    /** YouTube video id (the part after v=). Used only when no video file is uploaded. */
    public string $video_youtube_id = '';

    /** Path on the `public` disk to a self-hosted feature video, or null for the bundled clip. */
    public ?string $video_file = null;

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

    /** Absolute URL for the about-teaser portrait, falling back to the bundled photo. */
    public function aboutImageUrl(): string
    {
        return $this->about_image && Storage::disk('public')->exists($this->about_image)
            ? Storage::disk('public')->url($this->about_image)
            : asset('images/about-talib.webp');
    }

    /**
     * Absolute URL for the feature video: an uploaded file if present, otherwise
     * the bundled campaign clip. Returns null only when a YouTube id is set and
     * should take over instead.
     */
    public function videoUrl(): ?string
    {
        if ($this->video_file && Storage::disk('public')->exists($this->video_file)) {
            return Storage::disk('public')->url($this->video_file);
        }

        if ($this->video_youtube_id !== '') {
            return null;
        }

        return asset('videos/market-event.mp4');
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
     * Each slide carries both a full image (`img`, ≤1600w) and a 768w variant
     * (`img_sm`) that the front end serves to phones.
     *
     * @return array<int, array{img: string, img_sm: string, bg: string, pos: string}>
     */
    public function heroSlides(): array
    {
        $configured = collect($this->hero_slides)
            ->filter(fn ($slide) => filled($slide['image'] ?? null))
            ->map(function ($slide) {
                $full = Storage::disk('public')->url($slide['image']);
                $small = filled($slide['image_sm'] ?? null) && Storage::disk('public')->exists($slide['image_sm'])
                    ? Storage::disk('public')->url($slide['image_sm'])
                    : $full;

                return [
                    'img' => $full,
                    'img_sm' => $small,
                    'bg' => $slide['bg'] ?? '#0d1b38',
                    'pos' => $slide['position'] ?? 'center top',
                ];
            })
            ->values()
            ->all();

        if ($configured !== []) {
            return $configured;
        }

        // Slides are anchored to the top so faces/heads aren't cropped as the
        // hero height varies across viewports.
        $defaults = [
            ['file' => 'hero-rally', 'bg' => '#0d1b38'],
            ['file' => 'hero-talib-desk', 'bg' => '#0d1b38'],
            ['file' => 'hero-masquerade', 'bg' => '#0a1525'],
            ['file' => 'hero-supporters', 'bg' => '#112044'],
            ['file' => 'hero-hall', 'bg' => '#091422'],
            ['file' => 'hero-victory', 'bg' => '#0d1b38'],
        ];

        return array_map(fn ($slide) => [
            'img' => asset("images/{$slide['file']}.webp"),
            'img_sm' => asset("images/{$slide['file']}-sm.webp"),
            'bg' => $slide['bg'],
            'pos' => 'center top',
        ], $defaults);
    }
}
