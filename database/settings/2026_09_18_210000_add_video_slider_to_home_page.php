<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("home_page.{$key}", $value);

        $add('videos_eyebrow', 'Watch');
        $add('videos_headline', 'See the Work in Action');
        $add('videos_lead', "Openings, rallies and community moments from across the municipality — straight from the campaign's YouTube channel.");
        $add('videos_cta_label', 'Browse All Videos');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('home_page.videos_eyebrow');
        $this->migrator->deleteIfExists('home_page.videos_headline');
        $this->migrator->deleteIfExists('home_page.videos_lead');
        $this->migrator->deleteIfExists('home_page.videos_cta_label');
    }
};
