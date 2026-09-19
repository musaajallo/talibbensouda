<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Gallery entry ids, in the order the featured videos show in the home page's video section.
        $this->migrator->add('home_page.videos_order', []);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('home_page.videos_order');
    }
};
