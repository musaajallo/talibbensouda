<?php

namespace App\Support\Media;

/**
 * Shared helper for models that surface a Spatie Media Library image to the
 * public site.
 *
 * Two problems it solves:
 *
 *  1. Mixed content. Media Library builds URLs from filesystems.disks.public.url,
 *     which is derived from APP_URL at config-load time — before
 *     URL::forceScheme('https') runs in AppServiceProvider. Rebuilding the URL
 *     through url() on just the path guarantees the scheme matches the request.
 *
 *  2. Ghost rows. Media Library returns a URL for any `media` row even when the
 *     underlying file is missing (e.g. the DB was restored without storage/) or
 *     is a near-empty smoke-test upload. We reject anything under 8 KB / not on
 *     disk so the frontend renders its placeholder instead of a broken <img> —
 *     scaled down to 512 B for a named conversion, since a deliberately small
 *     thumbnail can legitimately weigh a couple of KB.
 *
 * Requires the consuming model to use Spatie\MediaLibrary\InteractsWithMedia.
 */
trait ResolvesPublicMediaUrl
{
    use ResolvesPublicDiskUrl;

    protected function publicMediaUrl(string $collection, ?string $conversion = null): ?string
    {
        $media = $this->getFirstMedia($collection);

        if ($media === null) {
            return null;
        }

        $usingConversion = $conversion !== null && $media->hasGeneratedConversion($conversion);
        $path = $usingConversion ? $media->getPath($conversion) : $media->getPath();
        $minBytes = $usingConversion ? 512 : 8 * 1024;

        if (! @is_file($path) || @filesize($path) < $minBytes) {
            return null;
        }

        $raw = $usingConversion ? $media->getUrl($conversion) : $media->getUrl();

        return $this->rebuildAgainstRequest($raw);
    }
}
