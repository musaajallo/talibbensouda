<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('events_page.event_types', ['Kanifing', 'Campaign']);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('events_page.event_types');
    }
};
