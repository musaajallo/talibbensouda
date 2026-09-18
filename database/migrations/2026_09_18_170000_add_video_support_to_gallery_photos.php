<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table): void {
            $table->string('type')->default('photo')->after('category')->comment('photo, video');
            $table->string('youtube_video_id')->nullable()->unique()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table): void {
            $table->dropColumn(['type', 'youtube_video_id']);
        });
    }
};
