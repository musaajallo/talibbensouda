<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site_chrome.signup_popup_enabled', true);
        $this->migrator->add('site_chrome.signup_popup_heading', "Join Talib's Campaign");
        $this->migrator->add('site_chrome.signup_popup_body', "We can win this, but we need your help. Leave your details and we'll keep you posted on how to get involved.");
        $this->migrator->add('site_chrome.signup_popup_button_label', "I'm In");
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('site_chrome.signup_popup_enabled');
        $this->migrator->deleteIfExists('site_chrome.signup_popup_heading');
        $this->migrator->deleteIfExists('site_chrome.signup_popup_body');
        $this->migrator->deleteIfExists('site_chrome.signup_popup_button_label');
    }
};
