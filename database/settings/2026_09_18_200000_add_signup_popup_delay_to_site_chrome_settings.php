<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Seconds of active browsing (across pages) before the pop-up may appear.
        $this->migrator->add('site_chrome.signup_popup_delay_seconds', 120);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('site_chrome.signup_popup_delay_seconds');
    }
};
