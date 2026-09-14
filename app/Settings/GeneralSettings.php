<?php

namespace App\Settings;

use App\Support\Media\ResolvesPublicDiskUrl;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    use ResolvesPublicDiskUrl;

    public string $site_name;

    public string $site_tagline;

    public string $contact_email;

    public ?string $contact_phone = null;

    /** "Based in" line on the contact page. */
    public string $based_in = 'Banjul, The Gambia';

    /** Path on the `public` disk to the site logo, or null to use the bundled asset. */
    public ?string $site_logo = null;

    /** Path on the `public` disk to the favicon, or null to use the bundled asset. */
    public ?string $favicon = null;

    public bool $registration_enabled;

    public bool $maintenance_mode;

    public static function group(): string
    {
        return 'general';
    }

    /** Absolute URL for an uploaded logo, or null when none is set. */
    public function uploadedLogoUrl(): ?string
    {
        return $this->site_logo && Storage::disk('public')->exists($this->site_logo)
            ? $this->resolvePublicDiskUrl($this->site_logo)
            : null;
    }

    /** Absolute URL for the logo, falling back to the bundled asset. */
    public function logoUrl(): string
    {
        return $this->uploadedLogoUrl() ?? asset('images/logo.svg');
    }

    /** Absolute URL for the favicon, falling back to the bundled asset. */
    public function faviconUrl(): string
    {
        return $this->favicon && Storage::disk('public')->exists($this->favicon)
            ? $this->resolvePublicDiskUrl($this->favicon)
            : asset('favicon.ico');
    }
}
