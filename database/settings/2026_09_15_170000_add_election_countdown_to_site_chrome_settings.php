<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site_chrome.election_countdown_enabled', true);
        $this->migrator->add('site_chrome.election_countdown_label', 'To the 2026 Presidential Election');
        $this->migrator->add('site_chrome.election_date', '2026-12-04');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('site_chrome.election_countdown_enabled');
        $this->migrator->deleteIfExists('site_chrome.election_countdown_label');
        $this->migrator->deleteIfExists('site_chrome.election_date');
    }
};
