<?php

namespace App\Support\YouTube;

use App\Models\GalleryPhoto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

/**
 * Adds YouTube videos to the Gallery from pasted links — for videos that are
 * NOT on the campaign's own channel and so never show up in the channel browser.
 */
class AddVideosByLink
{
    /** More than this in one go is almost certainly a mistake, and each link is a network call. */
    public const MAX_LINKS = 20;

    public function __construct(protected YouTubeOEmbed $oembed) {}

    /**
     * @return array{
     *     added: list<string>,
     *     existing: list<string>,
     *     promoted: list<string>,
     *     invalid: list<string>,
     *     unavailable: list<string>,
     *     unchecked: list<string>,
     * } captions of what was added / already there / newly featured, and the links that were
     *   not a YouTube link (invalid), have no public embeddable video (unavailable), or could
     *   not be checked because YouTube was unreachable (unchecked)
     */
    public function handle(string $links, string $category, bool $published, bool $featured): array
    {
        $parsed = YouTubeUrl::parseMany($links);

        $result = ['added' => [], 'existing' => [], 'promoted' => [], 'invalid' => $parsed['invalid'], 'unavailable' => [], 'unchecked' => []];

        foreach ($parsed['ids'] as $id) {
            // Already in the Gallery: never duplicate it (the column is unique), but if the
            // point of this paste was to feature them, honour that for the existing row.
            if ($existing = GalleryPhoto::where('youtube_video_id', $id)->first()) {
                $result['existing'][] = $existing->caption ?: $id;

                if ($featured && ! $existing->featured_on_home) {
                    $existing->update(['featured_on_home' => true]);
                    $result['promoted'][] = $existing->caption ?: $id;
                }

                continue;
            }

            try {
                $video = $this->oembed->lookup($id);
            } catch (ConnectionException) {
                $result['unchecked'][] = $id;

                continue;
            }

            if ($video === null) {
                $result['unavailable'][] = $id;

                continue;
            }

            GalleryPhoto::create([
                'caption' => Str::limit($video['title'], 250, '…'),
                'category' => $category,
                'type' => GalleryPhoto::TYPE_VIDEO,
                'youtube_video_id' => $id,
                'published' => $published,
                'featured_on_home' => $featured,
                'sort_order' => 0,
            ]);

            $result['added'][] = $video['title'];
        }

        return $result;
    }
}
