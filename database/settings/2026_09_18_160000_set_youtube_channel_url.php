<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('social.youtube', fn () => 'https://www.youtube.com/@talibforpresident');
    }

    public function down(): void
    {
        $this->migrator->update('social.youtube', fn () => null);
    }
};
