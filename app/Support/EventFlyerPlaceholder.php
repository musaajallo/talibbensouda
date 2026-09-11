<?php

namespace App\Support;

use App\Models\Event;
use Illuminate\Support\Str;

/**
 * Builds an on-brand SVG "flyer" for an event that has no uploaded image —
 * navy card, gold accents, the event's type / title / date. Deterministic
 * from the event's own fields, so it's cheap to regenerate per request and
 * safe to cache by URL (see routes/web.php: events.flyer-placeholder).
 */
class EventFlyerPlaceholder
{
    protected const WIDTH = 1200;

    protected const HEIGHT = 1500;

    protected const PAD = 110;

    protected const NAVY = '#0d1b38';

    protected const NAVY_DARK = '#080f20';

    protected const GOLD = '#c9a227';

    public static function svgFor(Event $event): string
    {
        return (new self)->render($event);
    }

    protected function render(Event $event): string
    {
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $pad = self::PAD;

        $eyebrow = mb_strtoupper($event->badge ?: 'Event');
        $titleLines = $this->wrap($event->title !== '' ? $event->title : 'Untitled event', maxCharsPerLine: 16, maxLines: 5);
        $date = trim("{$event->date_day} {$event->date_month} {$event->date_year}");
        $location = Str::limit((string) $event->location, 48);

        $lineHeight = 104;
        $titleTop = (int) round($h / 2 - (count($titleLines) * $lineHeight) / 2);

        $titleTspans = '';
        foreach ($titleLines as $i => $line) {
            $y = $titleTop + $i * $lineHeight;
            $titleTspans .= sprintf('<tspan x="%d" y="%d">%s</tspan>', $pad, $y, $this->esc($line));
        }

        $frameX = 56;
        $frameW = $w - ($frameX * 2);
        $frameH = $h - ($frameX * 2);

        $metaLine1Y = $h - 180;
        $metaLine2Y = $h - 138;
        $ruleY = $h - 260;
        $footerY = $h - 96;

        $diagonals = '';
        for ($i = 0; $i < 6; $i++) {
            $offset = $i * 60;
            $x1 = $w - 260 + $offset;
            $x2 = $w - $offset;
            $diagonals .= sprintf('<line x1="%d" y1="0" x2="%d" y2="260"/>', $x1, $x2);
        }

        $title = $this->esc($event->title !== '' ? $event->title : 'Untitled event');

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$w} {$h}" width="{$w}" height="{$h}" role="img" aria-label="{$title}">
            <defs>
                <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="{$this->esc(self::NAVY)}"/>
                    <stop offset="100%" stop-color="{$this->esc(self::NAVY_DARK)}"/>
                </linearGradient>
            </defs>

            <rect width="{$w}" height="{$h}" fill="url(#bg)"/>

            <g stroke="{$this->esc(self::GOLD)}" stroke-width="1" opacity="0.12">{$diagonals}</g>

            <rect x="{$frameX}" y="{$frameX}" width="{$frameW}" height="{$frameH}" fill="none" stroke="{$this->esc(self::GOLD)}" stroke-width="2" opacity="0.4"/>

            <text x="{$pad}" y="270" font-family="Arial, Helvetica, sans-serif" font-size="30" font-weight="700" letter-spacing="6" fill="{$this->esc(self::GOLD)}">{$this->esc($eyebrow)}</text>

            <text font-family="Arial, Helvetica, sans-serif" font-size="92" font-weight="800" fill="#ffffff">{$titleTspans}</text>

            <rect x="{$pad}" y="{$ruleY}" width="90" height="4" fill="{$this->esc(self::GOLD)}"/>

            <text x="{$pad}" y="{$metaLine1Y}" font-family="Arial, Helvetica, sans-serif" font-size="34" font-weight="700" fill="#ffffff">{$this->esc($date)}</text>
            <text x="{$pad}" y="{$metaLine2Y}" font-family="Arial, Helvetica, sans-serif" font-size="28" fill="rgba(255,255,255,0.65)">{$this->esc($location)}</text>

            <text x="{$pad}" y="{$footerY}" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="800" letter-spacing="3" fill="rgba(255,255,255,0.35)">TALIB BENSOUDA</text>
        </svg>
        SVG;
    }

    /**
     * @return list<string>
     */
    protected function wrap(string $text, int $maxCharsPerLine, int $maxLines): array
    {
        // Split any single word longer than a line (a long place name, a typo
        // with no spaces) so it can't run past the frame.
        $words = collect(preg_split('/\s+/', trim($text)) ?: [])
            ->flatMap(fn (string $word) => mb_strlen($word) > $maxCharsPerLine
                ? mb_str_split($word, $maxCharsPerLine)
                : [$word])
            ->all();

        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : "{$current} {$word}";

            if (mb_strlen($candidate) > $maxCharsPerLine && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $candidate;
            }

            if (count($lines) === $maxLines) {
                break;
            }
        }

        if ($current !== '' && count($lines) < $maxLines) {
            $lines[] = $current;
        }

        if (count($lines) === $maxLines && mb_strlen($current) > $maxCharsPerLine) {
            $last = array_pop($lines);
            $lines[] = mb_substr($last, 0, max(0, $maxCharsPerLine - 1)).'…';
        }

        return $lines ?: [$text];
    }

    protected function esc(string $text): string
    {
        return htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
