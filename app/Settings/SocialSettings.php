<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Social profile URLs shown in the site footer and on the contact page. A blank
 * value hides that icon everywhere.
 */
class SocialSettings extends Settings
{
    public ?string $facebook = null;

    public ?string $x = null;

    public ?string $instagram = null;

    public ?string $youtube = null;

    public ?string $whatsapp = null;

    public static function group(): string
    {
        return 'social';
    }

    /**
     * Non-empty links as [key => url], in display order.
     *
     * @return array<string, string>
     */
    public function links(): array
    {
        return array_filter([
            'facebook' => $this->facebook,
            'x' => $this->x,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'whatsapp' => $this->whatsapp,
        ], fn (?string $url): bool => filled($url));
    }
}
