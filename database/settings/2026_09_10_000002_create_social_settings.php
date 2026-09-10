<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook', 'https://www.facebook.com/MayorBensouda');
        $this->migrator->add('social.x', null);
        $this->migrator->add('social.instagram', null);
        $this->migrator->add('social.youtube', null);
        $this->migrator->add('social.whatsapp', null);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('social.facebook');
        $this->migrator->deleteIfExists('social.x');
        $this->migrator->deleteIfExists('social.instagram');
        $this->migrator->deleteIfExists('social.youtube');
        $this->migrator->deleteIfExists('social.whatsapp');
    }
};
