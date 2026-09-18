<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Milestone;

class EventController extends Controller
{
    public function index()
    {
        $now = now()->format('Ymd\THis\Z');

        $upcoming = Event::visibleUpcoming()->orderBy('ics_start')->get();

        $past = Event::where('is_upcoming', true)
            ->where('ics_end', '<', $now)
            ->orderByDesc('ics_start')
            ->get();

        $milestones = Milestone::published()->orderByDesc('occurred_on')->get();

        $hiddenUpcomingCount = Event::hiddenUpcomingCount();

        $calEvents = $upcoming->merge($past)->map(fn ($e) => [
            'day' => $e->js_day,
            'jsMonth' => $e->js_month,
            'year' => (int) $e->date_year,
            'title' => $e->title,
        ])->values()->all();

        return view('events', compact('upcoming', 'past', 'milestones', 'calEvents', 'hiddenUpcomingCount'));
    }

    public function show(Event $event)
    {
        // Same one-week visibility rule as index() — a far-future event
        // shouldn't leak into "related events" either.
        $related = Event::where('slug', '!=', $event->slug)
            ->visibleUpcoming()
            ->orderBy('ics_start')
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'related'));
    }
}
