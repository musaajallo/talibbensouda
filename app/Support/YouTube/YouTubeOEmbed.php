<?php

namespace App\Support\YouTube;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Looks a video up through YouTube's public oEmbed endpoint: no API key, no
 * quota. It answers only for videos that are public AND embeddable — exactly
 * what the Gallery lightbox needs — so a hit doubles as validation.
 */
class YouTubeOEmbed
{
    /**
     * @return array{title: string, author: ?string}|null null = YouTube says there is no such
     *                                                    public, embeddable video
     *
     * @throws ConnectionException when YouTube could not be reached or errored (try again later)
     */
    public function lookup(string $videoId): ?array
    {
        $response = Http::timeout(8)->get('https://www.youtube.com/oembed', [
            'url' => 'https://www.youtube.com/watch?v='.$videoId,
            'format' => 'json',
        ]);

        // "There is no such public, embeddable video": 400 = no video has that ID (what
        // YouTube really answers for a made-up ID — checked against the live endpoint),
        // 401 = embedding disabled, 403 = private, 404 = removed.
        if (in_array($response->status(), [400, 401, 403, 404], true)) {
            return null;
        }

        if (! $response->successful()) {
            throw new ConnectionException('YouTube oEmbed answered '.$response->status());
        }

        $title = trim((string) $response->json('title'));

        return $title === '' ? null : ['title' => $title, 'author' => $response->json('author_name')];
    }
}
