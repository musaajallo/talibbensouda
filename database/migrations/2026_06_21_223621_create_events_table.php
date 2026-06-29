<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('badge');           // 'diaspora' | 'gambia'
            $table->string('flag')->nullable(); // emoji flag character
            $table->string('date_day');
            $table->string('date_month');
            $table->string('date_year');
            $table->unsignedTinyInteger('js_day');
            $table->unsignedTinyInteger('js_month'); // 0-indexed JS month
            $table->string('location');
            $table->string('venue')->nullable();
            $table->text('description');
            $table->text('full_description')->nullable();
            $table->string('ics_start');
            $table->string('ics_end');
            $table->boolean('is_upcoming')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
