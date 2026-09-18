<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Days a dismissed pop-up stays away before it may ask again. 0 = never.
        $this->migrator->add('site_chrome.signup_popup_snooze_days', 14);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('site_chrome.signup_popup_snooze_days');
    }
};
