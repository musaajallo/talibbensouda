<?php

use App\Models\Event;

use function Pest\Laravel\get;

function makeEvent(bool $upcoming): Event
{
    return Event::create([
        'slug' => $upcoming ? 'upcoming-town-hall' : 'past-library-opening',
        'title' => $upcoming ? 'Upcoming Town Hall' : 'Library Opening 2023',
        'badge' => 'gambia',
        'date_day' => '01', 'date_month' => 'Jan', 'date_year' => $upcoming ? '2027' : '2023',
        'js_day' => 1, 'js_month' => 0,
        'location' => 'Kanifing',
        'description' => 'An event.',
        'ics_start' => '20270101T090000Z', 'ics_end' => '20270101T100000Z',
        'is_upcoming' => $upcoming,
        'sort_order' => 1,
    ]);
}

it('shows "Register Your Interest" on an upcoming event', function (): void {
    $event = makeEvent(upcoming: true);

    get(route('events.show', $event))
        ->assertOk()
        ->assertSee('Register Your Interest');
});

it('hides "Register Your Interest" on a past event', function (): void {
    $event = makeEvent(upcoming: false);

    get(route('events.show', $event))
        ->assertOk()
        ->assertDontSee('Register Your Interest');
});
