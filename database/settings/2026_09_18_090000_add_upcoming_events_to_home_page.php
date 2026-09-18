<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("home_page.{$key}", $value);

        $add('upcoming_eyebrow', 'On the Campaign Trail');
        $add('upcoming_headline', 'Upcoming Events');
        $add('upcoming_lead', 'Courtesy calls and rallies ahead of the presidential nominations — see where Talib is next.');
        $add('upcoming_cta_label', 'See All Events');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('home_page.upcoming_eyebrow');
        $this->migrator->deleteIfExists('home_page.upcoming_headline');
        $this->migrator->deleteIfExists('home_page.upcoming_lead');
        $this->migrator->deleteIfExists('home_page.upcoming_cta_label');
    }
};
