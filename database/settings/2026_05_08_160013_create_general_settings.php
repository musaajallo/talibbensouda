<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', config('app.name', 'Laravel'));
        $this->migrator->add('general.site_tagline', '');
        $this->migrator->add('general.contact_email', env('ADMIN_NOTIFICATION_EMAIL', 'admin@example.com'));
        $this->migrator->add('general.contact_phone', null);
        $this->migrator->add('general.registration_enabled', false);
        $this->migrator->add('general.maintenance_mode', false);
    }
};
