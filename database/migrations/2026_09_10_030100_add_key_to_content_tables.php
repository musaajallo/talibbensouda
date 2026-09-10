<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stable identifier for seed-managed rows so seeders can updateOrCreate on it
 * even when the visible title changes. Nullable — editor-created rows leave it
 * blank.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['projects', 'testimonials', 'community_photos', 'giving_programmes'] as $table) {
            Schema::table($table, function (Blueprint $t): void {
                $t->string('key')->nullable()->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach (['projects', 'testimonials', 'community_photos', 'giving_programmes'] as $table) {
            Schema::table($table, function (Blueprint $t): void {
                $t->dropColumn('key');
            });
        }
    }
};
