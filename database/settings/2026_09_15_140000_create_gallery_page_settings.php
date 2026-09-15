<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('gallery_page.photos_per_page', 24);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('gallery_page.photos_per_page');
    }
};
