<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'slug', 'title', 'badge', 'flag',
        'date_day', 'date_month', 'date_year',
        'js_day', 'js_month',
        'location', 'venue',
        'description', 'full_description',
        'ics_start', 'ics_end',
        'is_upcoming', 'sort_order',
    ];

    protected $casts = [
        'is_upcoming' => 'boolean',
        'js_day'      => 'integer',
        'js_month'    => 'integer',
        'sort_order'  => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function gcalUrl(): string
    {
        return 'https://www.google.com/calendar/render?' . http_build_query([
            'action'   => 'TEMPLATE',
            'text'     => $this->title,
            'dates'    => $this->ics_start . '/' . $this->ics_end,
            'details'  => $this->description,
            'location' => $this->location,
        ]);
    }

    public function outlookUrl(): string
    {
        $s = \DateTime::createFromFormat('Ymd\THis\Z', $this->ics_start, new \DateTimeZone('UTC'));
        $e = \DateTime::createFromFormat('Ymd\THis\Z', $this->ics_end,   new \DateTimeZone('UTC'));

        return 'https://outlook.live.com/calendar/0/deeplink/compose?' . http_build_query([
            'subject'  => $this->title,
            'startdt'  => $s->format('Y-m-d\TH:i:s'),
            'enddt'    => $e->format('Y-m-d\TH:i:s'),
            'body'     => $this->description,
            'location' => $this->location,
        ]);
    }

    public function icsUrl(): string
    {
        return route('events.ics', [
            'title'       => $this->title,
            'start'       => $this->ics_start,
            'end'         => $this->ics_end,
            'location'    => $this->location,
            'description' => $this->description,
        ]);
    }

    public function badgeLabel(): string
    {
        return $this->badge === 'diaspora' ? 'Diaspora Tour' : 'The Gambia';
    }
}
