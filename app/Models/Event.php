<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'slug', 'title', 'badge',
        'date_day', 'date_month', 'date_year',
        'js_day', 'js_month',
        'location', 'venue',
        'description', 'full_description',
        'ics_start', 'ics_end',
        'is_upcoming',
    ];

    protected $casts = [
        'is_upcoming' => 'boolean',
        'js_day' => 'integer',
        'js_month' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Read-only start/end, parsed from `ics_start` / `ics_end` (UTC,
     * `YYYYMMDDThhmmssZ` — the Gambia is UTC year-round). Writes go through the
     * admin form: HandlesEventSchedule assembles the ICS strings and the
     * derived display / calendar-grid columns from one date + two times.
     */
    protected function startsAt(): Attribute
    {
        return Attribute::make(
            get: fn (): ?CarbonImmutable => $this->ics_start
                ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $this->ics_start, 'UTC')
                : null,
        );
    }

    protected function endsAt(): Attribute
    {
        return Attribute::make(
            get: fn (): ?CarbonImmutable => $this->ics_end
                ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $this->ics_end, 'UTC')
                : null,
        );
    }

    /**
     * The event page renders `full_description` as HTML (the admin uses a rich
     * editor). Legacy / seeded values are blank-line-separated plain text — wrap
     * those in paragraphs on write so everything downstream is HTML.
     */
    protected function fullDescription(): Attribute
    {
        return Attribute::make(
            set: function (?string $value): ?string {
                if (blank($value) || Str::contains($value, '<')) {
                    return $value;
                }

                return collect(preg_split('/\n{2,}/', trim($value)))
                    ->map(fn (string $p): string => trim($p))
                    ->filter()
                    ->map(fn (string $p): string => '<p>'.e($p).'</p>')
                    ->implode('');
            },
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
        return (string) $this->badge;
    }
}
