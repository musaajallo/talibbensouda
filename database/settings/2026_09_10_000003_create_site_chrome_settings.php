<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site_chrome.join_party_label', 'Join Party');
        $this->migrator->add('site_chrome.join_party_url', 'https://unitemovementgambia.com/join');
        $this->migrator->add('site_chrome.footer_tagline', "The People's Mayor.");
        $this->migrator->add('site_chrome.copyright_name', 'Talib Bensouda');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('site_chrome.join_party_label');
        $this->migrator->deleteIfExists('site_chrome.join_party_url');
        $this->migrator->deleteIfExists('site_chrome.footer_tagline');
        $this->migrator->deleteIfExists('site_chrome.copyright_name');
    }
};
