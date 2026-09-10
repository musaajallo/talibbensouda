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
 *     disk so the frontend renders its placeholder instead of a broken <img>.
 *
 * Requires the consuming model to use Spatie\MediaLibrary\InteractsWithMedia.
 */
trait ResolvesPublicMediaUrl
{
    protected function publicMediaUrl(string $collection, ?string $conversion = null): ?string
    {
        $media = $this->getFirstMedia($collection);

        if ($media === null) {
            return null;
        }

        $path = $conversion !== null && $media->hasGeneratedConversion($conversion)
            ? $media->getPath($conversion)
            : $media->getPath();

        if (! @is_file($path) || @filesize($path) < 8 * 1024) {
            return null;
        }

        $raw = $conversion !== null && $media->hasGeneratedConversion($conversion)
            ? $media->getUrl($conversion)
            : $media->getUrl();

        $urlPath = parse_url($raw, PHP_URL_PATH);

        return $urlPath ? url($urlPath) : $raw;
    }
}
