<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::where('is_upcoming', true)
            ->orderBy('sort_order')
            ->get();

        $calEvents = $upcoming->map(fn ($e) => [
            'day' => $e->js_day,
            'jsMonth' => $e->js_month,
            'year' => (int) $e->date_year,
            'title' => $e->title,
        ])->values()->all();

        return view('events', compact('upcoming', 'calEvents'));
    }

    public function show(Event $event)
    {
        $related = Event::where('slug', '!=', $event->slug)
            ->where('is_upcoming', true)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'related'));
    }
}
