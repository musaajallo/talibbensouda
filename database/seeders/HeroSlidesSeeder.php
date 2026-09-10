<?php

namespace Database\Seeders;

use App\Settings\HomePageSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Seeds the home hero slider as editable CMS rows. Copies the bundled hero
 * photos (full + 768w `-sm` variant) onto the `public` media disk and points
 * `home_page.hero_slides` at them, so an admin can reorder, replace or remove
 * slides in the panel (Page content → Home page → Hero).
 *
 * First run: seeds the full bundled set. Later runs: no-op, except that a slide
 * still pointing at a bundled photo but missing its `-sm` variant is upgraded
 * in place (so installs seeded before responsive hero images get them without a
 * manual reset). Admin-uploaded slides are never touched.
 */
class HeroSlidesSeeder extends Seeder
{
    /** @var list<array{file: string, bg: string}> */
    protected array $bundled = [
        ['file' => 'hero-rally', 'bg' => '#0d1b38'],
        ['file' => 'hero-talib-desk', 'bg' => '#0d1b38'],
        ['file' => 'hero-masquerade', 'bg' => '#0a1525'],
        ['file' => 'hero-supporters', 'bg' => '#112044'],
        ['file' => 'hero-hall', 'bg' => '#091422'],
        ['file' => 'hero-victory', 'bg' => '#0d1b38'],
    ];

    public function run(): void
    {
        $settings = app(HomePageSettings::class);

        if (! empty($settings->hero_slides)) {
            $this->backfillSmallVariants($settings);

            return;
        }

        $settings->hero_slides = array_map(fn ($slide) => [
            'image' => $this->copyToDisk($slide['file'].'.webp'),
            'image_sm' => $this->copyToDisk($slide['file'].'-sm.webp'),
            'bg' => $slide['bg'],
            'position' => 'center top',
        ], $this->bundled);

        $settings->save();
    }

    /**
     * Add the `-sm` key to any existing slide that still points at a bundled
     * photo and doesn't have one yet.
     */
    protected function backfillSmallVariants(HomePageSettings $settings): void
    {
        $changed = false;

        $slides = array_map(function ($slide) use (&$changed) {
            $image = $slide['image'] ?? null;

            if (filled($slide['image_sm'] ?? null) || ! is_string($image)) {
                return $slide;
            }

            if (! preg_match('#^hero/(hero-[a-z-]+)\.webp$#', $image, $m)) {
                return $slide; // an admin upload — leave it alone
            }

            $changed = true;
            $slide['image_sm'] = $this->copyToDisk($m[1].'-sm.webp');

            return $slide;
        }, $settings->hero_slides);

        if ($changed) {
            $settings->hero_slides = $slides;
            $settings->save();
        }
    }

    /**
     * Copy a bundled hero photo onto the `public` media disk, once.
     * Returns the disk-relative path (also returned when the source is missing).
     */
    protected function copyToDisk(string $file): string
    {
        $source = public_path('images/'.$file);
        $target = 'hero/'.$file;

        if (File::exists($source) && ! Storage::disk('public')->exists($target)) {
            Storage::disk('public')->put($target, File::get($source));
        }

        return $target;
    }
}
