<?php

namespace App\Support\Media;

use Illuminate\Support\Facades\Storage;

/**
 * Rebuilds a `public`-disk URL through url() against the current request's
 * host, rather than the host baked into filesystems.php at config-load time
 * (derived from APP_URL). That config value drifts from the app's actual
 * origin whenever the dev server lands on a different port (e.g. the
 * default is already taken by another project) — without this, an <img>
 * built straight from Storage::disk('public')->url() points at a dead host
 * while everything else on the page, which resolves through url(), quietly
 * self-corrects.
 */
trait ResolvesPublicDiskUrl
{
    protected function rebuildAgainstRequest(string $absoluteUrl): string
    {
        $urlPath = parse_url($absoluteUrl, PHP_URL_PATH);

        return $urlPath ? url($urlPath) : $absoluteUrl;
    }

    protected function resolvePublicDiskUrl(string $path): string
    {
        return $this->rebuildAgainstRequest(Storage::disk('public')->url($path));
    }
}
