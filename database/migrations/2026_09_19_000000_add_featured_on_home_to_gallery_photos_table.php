<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table): void {
            // Only meaningful for video rows: when any published video has this set,
            // the home page's video slider shows just those (see GalleryPhoto::forHomeSlider()).
            $table->boolean('featured_on_home')->default(false)->after('published');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table): void {
            $table->dropColumn('featured_on_home');
        });
    }
};
