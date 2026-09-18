<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Milestone;

class EventController extends Controller
{
    public function index()
    {
        $now = now()->format('Ymd\THis\Z');
        // Public visibility rule: an event only appears once it's within a
        // week of happening — further out, the row exists (admin-editable,
        // seeded) but isn't shown. Doesn't affect $past or the homepage.
        $visibleFrom = now()->addWeek()->format('Ymd\THis\Z');

        $upcoming = Event::where('is_upcoming', true)
            ->where('ics_end', '>=', $now)
            ->where('ics_start', '<=', $visibleFrom)
            ->orderBy('ics_start')
            ->get();

        $past = Event::where('is_upcoming', true)
            ->where('ics_end', '<', $now)
            ->orderByDesc('ics_start')
            ->get();

        $milestones = Milestone::published()->orderByDesc('occurred_on')->get();

        $calEvents = $upcoming->merge($past)->map(fn ($e) => [
            'day' => $e->js_day,
            'jsMonth' => $e->js_month,
            'year' => (int) $e->date_year,
            'title' => $e->title,
        ])->values()->all();

        return view('events', compact('upcoming', 'past', 'milestones', 'calEvents'));
    }

    public function show(Event $event)
    {
        // Same one-week visibility rule as index() — a far-future event
        // shouldn't leak into "related events" either.
        $related = Event::where('slug', '!=', $event->slug)
            ->where('is_upcoming', true)
            ->where('ics_end', '>=', now()->format('Ymd\THis\Z'))
            ->where('ics_start', '<=', now()->addWeek()->format('Ymd\THis\Z'))
            ->orderBy('ics_start')
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'related'));
    }
}
