<?php

namespace App\Support\YouTube;

/**
 * Pulls a video ID out of whatever a person pastes: a watch link (with or
 * without extra parameters), youtu.be, Shorts, embed and live links, on
 * youtube.com / m. / music. / youtube-nocookie.com — or a bare 11-character ID.
 */
class YouTubeUrl
{
    /** YouTube video IDs are 11 characters of A-Z a-z 0-9 _ - (so one can start with a dash). */
    public const ID_PATTERN = '/^[A-Za-z0-9_-]{11}$/';

    public static function extractId(string $input): ?string
    {
        $input = trim($input);

        if ($input === '') {
            return null;
        }

        if (preg_match(self::ID_PATTERN, $input) === 1) {
            return $input;
        }

        $parts = parse_url(str_contains($input, '://') ? $input : 'https://'.$input);

        if (! is_array($parts) || empty($parts['host'])) {
            return null;
        }

        $host = (string) preg_replace('/^(www\.|m\.|music\.)/', '', strtolower($parts['host']));
        $segments = array_values(array_filter(explode('/', $parts['path'] ?? '/'), fn (string $s): bool => $s !== ''));

        $candidate = match (true) {
            $host === 'youtu.be' => $segments[0] ?? null,
            in_array($host, ['youtube.com', 'youtube-nocookie.com'], true) => self::fromYoutubeDotCom($segments, $parts['query'] ?? ''),
            default => null,
        };

        return is_string($candidate) && preg_match(self::ID_PATTERN, $candidate) === 1 ? $candidate : null;
    }

    /**
     * Every link in a pasted block — separated by new lines, spaces or commas —
     * with duplicates removed and the original order kept.
     *
     * @return array{ids: list<string>, invalid: list<string>}
     */
    public static function parseMany(string $text): array
    {
        $ids = [];
        $invalid = [];

        foreach (preg_split('/[\s,]+/', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $token) {
            $id = self::extractId($token);

            if ($id === null) {
                $invalid[] = $token;
            } elseif (! in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return ['ids' => $ids, 'invalid' => $invalid];
    }

    /**
     * @param  list<string>  $segments
     */
    protected static function fromYoutubeDotCom(array $segments, string $query): ?string
    {
        if (($segments[0] ?? null) === 'watch') {
            parse_str($query, $params);

            return is_string($params['v'] ?? null) ? $params['v'] : null;
        }

        return in_array($segments[0] ?? null, ['embed', 'shorts', 'live', 'v'], true) ? ($segments[1] ?? null) : null;
    }
}
