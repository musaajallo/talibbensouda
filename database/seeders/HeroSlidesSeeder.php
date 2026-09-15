<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Seeds the home hero slider with the bundled photos as real HeroSlide rows,
 * so an admin can reorder, replace or remove slides in the panel
 * (Content → Hero slides) without touching this seeder.
 *
 * Create-if-missing: no-ops once any HeroSlide row exists, so admin edits
 * (including deleting down to zero slides, which falls back to these same
 * bundled photos — see HeroSlide::heroSlides()) are never overwritten.
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
        if (HeroSlide::query()->exists()) {
            return;
        }

        foreach ($this->bundled as $i => $slide) {
            $source = public_path('images/'.$slide['file'].'.webp');

            if (! File::exists($source)) {
                continue;
            }

            $heroSlide = HeroSlide::create([
                'fallback_colour' => $slide['bg'],
                'position' => 'center top',
                'sort_order' => $i,
            ]);

            $heroSlide->addMedia($source)->preservingOriginal()->toMediaCollection('image');
        }
    }
}
