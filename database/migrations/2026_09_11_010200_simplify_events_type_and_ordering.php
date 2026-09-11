<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Events are now ordered newest-first by date (not `sort_order`), the emoji
 * `flag` is dropped, and the type is a free label managed under
 * EventsPageSettings rather than the hardcoded `gambia` / `diaspora` enum.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('events')->where('badge', 'gambia')->update(['badge' => 'Kanifing']);
        DB::table('events')->where('badge', 'diaspora')->update(['badge' => 'Campaign']);

        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn(['flag', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->string('flag')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        DB::table('events')->where('badge', 'Kanifing')->update(['badge' => 'gambia']);
        DB::table('events')->where('badge', 'Campaign')->update(['badge' => 'diaspora']);
    }
};
