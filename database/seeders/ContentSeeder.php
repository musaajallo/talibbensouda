<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * All public-site content: the flagship projects, events, gallery, community
 * photos, testimonials, giving-back programmes and the hero slider.
 *
 * Runs on every deploy (see .forge/deploy.sh). Every seeder here is
 * create-if-missing only — an existing row (or configured hero slider) is
 * never overwritten, so anything edited in the admin panel is preserved.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EventSeeder::class,
            GalleryPhotoSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
            CommunityPhotoSeeder::class,
            GivingProgrammeSeeder::class,
            HeroSlidesSeeder::class,
        ]);
    }
}
