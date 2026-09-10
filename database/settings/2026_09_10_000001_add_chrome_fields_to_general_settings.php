<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.based_in', 'Banjul, The Gambia');
        $this->migrator->add('general.response_time', 'Within 48 hours');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.favicon', null);

        // Backfill the values that were previously hardcoded in the Blade views.
        $this->migrator->update('general.site_name', fn ($current) => $current === 'Laravel' ? 'Talib Bensouda' : $current);
        $this->migrator->update('general.site_tagline', fn ($current) => filled($current) ? $current : "The People's Mayor.");
        $this->migrator->update('general.contact_email', fn ($current) => str_contains((string) $current, 'example.com') ? 'info@talibahmedbensouda.com' : $current);
    }

    public function down(): void
    {
        $this->migrator->delete('general.based_in');
        $this->migrator->delete('general.response_time');
        $this->migrator->delete('general.site_logo');
        $this->migrator->delete('general.favicon');
    }
};
