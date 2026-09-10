<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // The site's real domain is talibahmedbensouda.com, not the earlier
        // placeholder talibbensouda.gm.
        $this->migrator->update(
            'general.contact_email',
            fn ($current) => str_replace('talibbensouda.gm', 'talibahmedbensouda.com', (string) $current),
        );
    }
};
