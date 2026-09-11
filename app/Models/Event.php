<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'slug', 'title', 'badge', 'flag',
        'date_day', 'date_month', 'date_year',
        'js_day', 'js_month',
        'starts_at', 'ends_at',
        'location', 'venue',
        'description', 'full_description',
        'ics_start', 'ics_end',
        'is_upcoming', 'sort_order',
    ];

    protected $casts = [
        'is_upcoming' => 'boolean',
        'js_day' => 'integer',
        'js_month' => 'integer',
        'sort_order' => 'integer',
    ];

    /** Exposed so the Filament form picks them up when editing. */
    protected $appends = ['starts_at', 'ends_at'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Start of the event. Backed by `ics_start` (UTC, `YYYYMMDDThhmmssZ`); writing
     * it also fills the display strings (`date_day`/`date_month`/`date_year`) and
     * the calendar-grid integers (`js_day`, `js_month` — 0-indexed). The Gambia is
     * UTC year-round, so wall-clock time is stored as-is.
     */
    protected function startsAt(): Attribute
    {
        return Attribute::make(
            get: fn (): ?CarbonImmutable => $this->ics_start
                ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $this->ics_start, 'UTC')
                : null,
            set: function ($value): array {
                if (blank($value)) {
                    return ['ics_start' => null];
                }

                $d = CarbonImmutable::parse($value);

                return [
                    'ics_start' => $d->format('Ymd\THis\Z'),
                    'date_day' => $d->format('j'),
                    'date_month' => $d->format('M'),   // "Dec" — matches the compact date card
                    'date_year' => $d->format('Y'),
                    'js_day' => (int) $d->format('j'),
                    'js_month' => (int) $d->format('n') - 1,
                ];
            },
        );
    }

    /** End of the event. Backed by `ics_end` (UTC, `YYYYMMDDThhmmssZ`). */
    protected function endsAt(): Attribute
    {
        return Attribute::make(
            get: fn (): ?CarbonImmutable => $this->ics_end
                ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $this->ics_end, 'UTC')
                : null,
            set: fn ($value): array => [
                'ics_end' => blank($value) ? null : CarbonImmutable::parse($value)->format('Ymd\THis\Z'),
            ],
        );
    }

    public function gcalUrl(): string
    {
        return 'https://www.google.com/calendar/render?'.http_build_query([
            'action' => 'TEMPLATE',
            'text' => $this->title,
            'dates' => $this->ics_start.'/'.$this->ics_end,
            'details' => $this->description,
            'location' => $this->location,
        ]);
    }

    public function outlookUrl(): string
    {
        $s = \DateTime::createFromFormat('Ymd\THis\Z', $this->ics_start, new \DateTimeZone('UTC'));
        $e = \DateTime::createFromFormat('Ymd\THis\Z', $this->ics_end, new \DateTimeZone('UTC'));

        return 'https://outlook.live.com/calendar/0/deeplink/compose?'.http_build_query([
            'subject' => $this->title,
            'startdt' => $s->format('Y-m-d\TH:i:s'),
            'enddt' => $e->format('Y-m-d\TH:i:s'),
            'body' => $this->description,
            'location' => $this->location,
        ]);
    }

    public function icsUrl(): string
    {
        return route('events.ics', [
            'title' => $this->title,
            'start' => $this->ics_start,
            'end' => $this->ics_end,
            'location' => $this->location,
            'description' => $this->description,
        ]);
    }

    public function badgeLabel(): string
    {
        return $this->badge === 'diaspora' ? 'Campaign' : 'Kanifing';
    }
}
