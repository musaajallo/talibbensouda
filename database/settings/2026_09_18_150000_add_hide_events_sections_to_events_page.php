<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('events_page.hide_events_sections', false);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('events_page.hide_events_sections');
    }
};
