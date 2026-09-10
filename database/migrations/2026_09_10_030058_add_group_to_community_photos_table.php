<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_photos', function (Blueprint $table): void {
            $table->string('group')->default('municipality')->after('id')
                ->comment('municipality = home + People\'s Mayor; community-support = Giving Back');
        });
    }

    public function down(): void
    {
        Schema::table('community_photos', function (Blueprint $table): void {
            $table->dropColumn('group');
        });
    }
};
