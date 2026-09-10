<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('home_page.about_image', null);
        $this->migrator->add('home_page.video_file', null);
    }

    public function down(): void
    {
        $this->migrator->delete('home_page.about_image');
        $this->migrator->delete('home_page.video_file');
    }
};
