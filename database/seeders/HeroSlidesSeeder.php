<?php

namespace Database\Seeders;

use App\Settings\HomePageSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Seeds the home hero slider as editable CMS rows. Copies the bundled hero
 * photos onto the `public` media disk and points `home_page.hero_slides` at
 * them, so an admin can reorder, replace or remove slides in the panel
 * (Page content → Home page → Hero). No-ops once slides are configured.
 */
class HeroSlidesSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(HomePageSettings::class);

        if (! empty($settings->hero_slides)) {
            return;
        }

        $slides = [
            ['file' => 'hero-rally.webp', 'bg' => '#0d1b38'],
            ['file' => 'hero-talib-desk.webp', 'bg' => '#0d1b38'],
            ['file' => 'hero-masquerade.webp', 'bg' => '#0a1525'],
            ['file' => 'hero-supporters.webp', 'bg' => '#112044'],
            ['file' => 'hero-hall.webp', 'bg' => '#091422'],
            ['file' => 'hero-victory.webp', 'bg' => '#0d1b38'],
        ];

        $rows = [];

        foreach ($slides as $slide) {
            $source = public_path('images/'.$slide['file']);
            $target = 'hero/'.$slide['file'];

            if (File::exists($source) && ! Storage::disk('public')->exists($target)) {
                Storage::disk('public')->put($target, File::get($source));
            }

            $rows[] = [
                'image' => $target,
                'bg' => $slide['bg'],
                'position' => 'center top',
            ];
        }

        $settings->hero_slides = $rows;
        $settings->save();
    }
}
