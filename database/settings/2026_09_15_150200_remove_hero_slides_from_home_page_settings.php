<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * The hero slider moved to its own HeroSlide resource/table — see
 * 2026_09_15_150000_create_hero_slides_table.php and
 * 2026_09_15_150100_migrate_hero_slides_from_settings.php, which must run
 * first (both sort earlier by timestamp) to carry over whatever was here.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->deleteIfExists('home_page.hero_slides');
    }

    public function down(): void
    {
        $this->migrator->add('home_page.hero_slides', []);
    }
};
