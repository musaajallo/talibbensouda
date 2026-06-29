<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public string $site_tagline;
    public string $contact_email;
    public ?string $contact_phone = null;
    public bool $registration_enabled;
    public bool $maintenance_mode;

    public static function group(): string
    {
        return 'general';
    }
}
