<?php

namespace App\Models;

use App\Support\Media\ResolvesPublicMediaUrl;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;
    use ResolvesPublicMediaUrl;

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
     * Public-visibility rule (2026-09): a scheduled event only appears
     * anywhere on the site once it's within a week of happening — the
     * events page, its calendar, "related events", and the homepage's
     * "Upcoming Events" section all use this same scope, so the window can
     * only ever be changed in one place. `is_upcoming` is unrelated — that's
     * a published/visibility toggle (see the admin form), not a chronology
     * check; this scope is the chronology check.
     */
    public function scopeVisibleUpcoming(Builder $query): Builder
    {
        $now = now()->format('Ymd\THis\Z');
        $visibleFrom = now()->addWeek()->format('Ymd\THis\Z');

        return $query->where('is_upcoming', true)
            ->where('ics_end', '>=', $now)
            ->where('ics_start', '<=', $visibleFrom);
    }

    /**
     * Scheduled events that exist and are published but sit beyond the
     * one-week visibility window above — i.e. "still coming, not shown yet".
     * Used for the homepage/events-page teaser count.
     */
    public static function hiddenUpcomingCount(): int
    {
        return static::where('is_upcoming', true)
            ->where('ics_start', '>', now()->addWeek()->format('Ymd\THis\Z'))
            ->count();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('flyer')->singleFile()->useDisk('public');
    }

    /**
     * A small 4:5 crop for list/card contexts (the events index) — the full
     * upload is a 1200x1500 poster, far more than an 80px-wide card needs.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('flyer')
            ->width(240)
            ->height(300)
            ->sharpen(10);
    }

    /** The uploaded flyer, or null if none was set. */
    public function flyerUrl(): ?string
    {
        return $this->publicMediaUrl('flyer');
    }

    /** The uploaded flyer's small card crop, or null if none was set. */
    public function flyerThumbUrl(): ?string
    {
        return $this->publicMediaUrl('flyer', 'thumb');
    }

    /**
     * The flyer thumb for card/list contexts, or the placeholder — always
     * usable as an <img> src. The placeholder is a cheap vector regardless of
     * size, so it doesn't need its own small variant.
     */
    public function flyerThumbImageUrl(): string
    {
        return $this->flyerThumbUrl() ?? route('events.flyer-placeholder', $this);
    }

    /** The uploaded flyer, or a generated on-brand placeholder — always usable as an <img> src. */
    public function flyerImageUrl(): string
    {
        return $this->flyerUrl() ?? route('events.flyer-placeholder', $this);
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
