<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Settings for /gallery. For now just how many photos to show per page
 * before pagination kicks in.
 */
class GalleryPageSettings extends Settings
{
    public int $photos_per_page = 24;

    public static function group(): string
    {
        return 'gallery_page';
    }
}
