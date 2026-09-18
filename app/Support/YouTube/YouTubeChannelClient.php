<?php

namespace App\Support\YouTube;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Thin wrapper around the bits of the YouTube Data API v3 that
 * ImportYoutubeVideos needs — channel/uploads-playlist resolution (cached
 * long-term, these never change) and listing/searching the channel's videos.
 *
 * `playlistItems.list` (listVideos) costs 1 quota unit per call; `search.list`
 * (searchVideos) costs 100 — the free daily quota is 10,000 units, so lean on
 * listVideos and only use search when the admin actually types a query.
 */
class YouTubeChannelClient
{
    protected const BASE_URL = 'https://www.googleapis.com/youtube/v3';

    public function isConfigured(): bool
    {
        return filled($this->apiKey());
    }

    protected function apiKey(): string
    {
        return (string) config('services.youtube.api_key');
    }

    protected function handle(): string
    {
        return (string) config('services.youtube.channel_handle');
    }

    /** The channel's stable ID, resolved from its @handle. Cached 30 days — this never changes. */
    public function channelId(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        return Cache::remember(
            'youtube.channel_id.'.$this->handle(),
            now()->addDays(30),
            function (): ?string {
                $response = Http::get(self::BASE_URL.'/channels', [
                    'part' => 'id',
                    'forHandle' => $this->handle(),
                    'key' => $this->apiKey(),
                ]);

                return $response->successful() ? $response->json('items.0.id') : null;
            }
        );
    }

    /** The playlist ID that holds every one of the channel's uploads, in order. Cached 30 days. */
    public function uploadsPlaylistId(): ?string
    {
        $channelId = $this->channelId();

        if (! $channelId) {
            return null;
        }

        return Cache::remember(
            "youtube.uploads_playlist.{$channelId}",
            now()->addDays(30),
            function () use ($channelId): ?string {
                $response = Http::get(self::BASE_URL.'/channels', [
                    'part' => 'contentDetails',
                    'id' => $channelId,
                    'key' => $this->apiKey(),
                ]);

                return $response->successful()
                    ? $response->json('items.0.contentDetails.relatedPlaylists.uploads')
                    : null;
            }
        );
    }

    /**
     * The channel's uploads, newest first.
     *
     * @return array{videos: list<array{id: string, title: string, published_at: ?string, thumbnail: ?string}>, next_page_token: ?string}
     */
    public function listVideos(?string $pageToken = null, int $maxResults = 24): array
    {
        $playlistId = $this->uploadsPlaylistId();

        if (! $playlistId) {
            return ['videos' => [], 'next_page_token' => null];
        }

        $response = Http::get(self::BASE_URL.'/playlistItems', array_filter([
            'part' => 'snippet',
            'playlistId' => $playlistId,
            'maxResults' => $maxResults,
            'pageToken' => $pageToken,
            'key' => $this->apiKey(),
        ]));

        if (! $response->successful()) {
            return ['videos' => [], 'next_page_token' => null];
        }

        return [
            'videos' => $this->normaliseItems($response->json('items', []), fn (array $item) => $item['snippet']['resourceId']['videoId'] ?? null),
            'next_page_token' => $response->json('nextPageToken'),
        ];
    }

    /**
     * Videos on the channel matching a title search, newest first.
     *
     * @return array{videos: list<array{id: string, title: string, published_at: ?string, thumbnail: ?string}>, next_page_token: ?string}
     */
    public function searchVideos(string $query, ?string $pageToken = null, int $maxResults = 24): array
    {
        $channelId = $this->channelId();

        if (! $channelId) {
            return ['videos' => [], 'next_page_token' => null];
        }

        $response = Http::get(self::BASE_URL.'/search', array_filter([
            'part' => 'snippet',
            'channelId' => $channelId,
            'q' => $query,
            'type' => 'video',
            'order' => 'date',
            'maxResults' => $maxResults,
            'pageToken' => $pageToken,
            'key' => $this->apiKey(),
        ]));

        if (! $response->successful()) {
            return ['videos' => [], 'next_page_token' => null];
        }

        return [
            'videos' => $this->normaliseItems($response->json('items', []), fn (array $item) => $item['id']['videoId'] ?? null),
            'next_page_token' => $response->json('nextPageToken'),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  \Closure(array<string, mixed>): (string|null)  $resolveId
     * @return list<array{id: string, title: string, published_at: ?string, thumbnail: ?string}>
     */
    protected function normaliseItems(array $items, \Closure $resolveId): array
    {
        return collect($items)
            ->map(function (array $item) use ($resolveId): array {
                $snippet = $item['snippet'] ?? [];

                return [
                    'id' => $resolveId($item),
                    'title' => $snippet['title'] ?? '',
                    'published_at' => $snippet['publishedAt'] ?? null,
                    'thumbnail' => $snippet['thumbnails']['medium']['url']
                        ?? $snippet['thumbnails']['default']['url']
                        ?? null,
                ];
            })
            ->filter(fn (array $video): bool => filled($video['id']))
            ->values()
            ->all();
    }
}
