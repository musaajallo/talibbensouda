<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Milestone;
use App\Settings\EventsPageSettings;

class EventController extends Controller
{
    public function index()
    {
        $eventsHidden = app(EventsPageSettings::class)->hide_events_sections;

        if ($eventsHidden) {
            $upcoming = collect();
            $past = collect();
            $hiddenUpcomingCount = 0;
            $calEvents = [];
        } else {
            $now = now()->format('Ymd\THis\Z');

            $upcoming = Event::visibleUpcoming()->orderBy('ics_start')->get();

            $past = Event::where('is_upcoming', true)
                ->where('ics_end', '<', $now)
                ->orderByDesc('ics_start')
                ->get();

            $hiddenUpcomingCount = Event::hiddenUpcomingCount();

            $calEvents = $upcoming->merge($past)->map(fn ($e) => [
                'day' => $e->js_day,
                'jsMonth' => $e->js_month,
                'year' => (int) $e->date_year,
                'title' => $e->title,
            ])->values()->all();
        }

        $milestones = Milestone::published()->orderByDesc('occurred_on')->get();

        return view('events', compact('upcoming', 'past', 'milestones', 'calEvents', 'hiddenUpcomingCount', 'eventsHidden'));
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
