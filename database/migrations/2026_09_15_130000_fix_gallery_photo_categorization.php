<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Two GalleryPhoto rows didn't match the pattern the rest of their category
 * follows: every other foundation-stone-laying photo (Serrekunda West mini-
 * stadium, Ya Fatou Njie market, the generic "Foundation stone laying
 * ceremony") is filed under Projects, but the BMZ Germany one was filed
 * under Partners; every other "visiting dignitary" photo (diplomatic visit,
 * international visitors — ribbon ceremony) is filed under Partners, but
 * this one was filed under Community. GalleryPhotoSeeder's firstOrCreate
 * never touches an existing row, so this corrects installs that already
 * seeded the old categories.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('gallery_photos')
            ->where('caption', 'BMZ Germany — Bakoteh Dumpsite Project foundation stone, 2021')
            ->update(['category' => 'Projects']);

        DB::table('gallery_photos')
            ->where('caption', 'Night reception with an international visitor')
            ->update(['category' => 'Partners']);
    }

    public function down(): void
    {
        DB::table('gallery_photos')
            ->where('caption', 'BMZ Germany — Bakoteh Dumpsite Project foundation stone, 2021')
            ->update(['category' => 'Partners']);

        DB::table('gallery_photos')
            ->where('caption', 'Night reception with an international visitor')
            ->update(['category' => 'Community']);
    }
};
