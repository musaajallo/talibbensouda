<?php

use App\Models\Event;
use App\Models\Milestone;
use App\Settings\EventsPageSettings;
use Carbon\CarbonImmutable;

use function Pest\Laravel\get;

function eventOn(string $isoDate, string $slug): Event
{
    $d = CarbonImmutable::parse($isoDate);

    return Event::create([
        'slug' => $slug,
        'title' => $slug,
        'badge' => 'Kanifing',
        'date_day' => $d->format('j'), 'date_month' => $d->format('M'), 'date_year' => $d->format('Y'),
        'js_day' => (int) $d->format('j'), 'js_month' => (int) $d->format('n') - 1,
        'location' => 'Kanifing',
        'description' => 'An event.',
        'ics_start' => $d->format('Ymd\THis\Z'),
        'ics_end' => $d->addHour()->format('Ymd\THis\Z'),
        'is_upcoming' => true,
    ]);
}

it('only shows an upcoming event once it is within a week of happening', function (): void {
    eventOn(now()->addDays(3)->toDateString(), 'soon');
    eventOn(now()->addDays(20)->toDateString(), 'far-off');

    get('/events')->assertOk()->assertSee('soon')->assertDontSee('far-off');
});

it('teases the count of events hidden beyond the one-week window', function (): void {
    eventOn(now()->addDays(20)->toDateString(), 'far-off-1');
    eventOn(now()->addDays(25)->toDateString(), 'far-off-2');
    eventOn(now()->addDays(30)->toDateString(), 'far-off-3');

    get('/events')->assertOk()->assertSee('+3')->assertSee('come back soon');
});

it('hides the Past Events section entirely when nothing has happened yet', function (): void {
    eventOn(now()->addDays(3)->toDateString(), 'only-upcoming');

    get('/events')->assertOk()->assertDontSee('Past Events');
});

it('hides Events entirely and shows only Milestones when the settings kill switch is on', function (): void {
    eventOn(now()->addDays(3)->toDateString(), 'switched-off-upcoming');
    eventOn(now()->subDays(3)->toDateString(), 'switched-off-past');
    Milestone::create([
        'slug' => 'library-hub', 'title' => 'Milestone Should Still Show',
        'occurred_on' => '2024-12-01', 'location' => 'Kanifing',
        'description' => 'D45m library inaugurated.', 'published' => true,
    ]);

    $settings = app(EventsPageSettings::class);
    $settings->hide_events_sections = true;
    $settings->save();

    get('/events')
        ->assertOk()
        ->assertDontSee('switched-off-upcoming')
        ->assertDontSee('switched-off-past')
        ->assertDontSee('Upcoming Events')
        ->assertDontSee('Past Events')
        ->assertSee('Milestone Should Still Show');
});

it('lists events newest-first regardless of creation order', function (): void {
    // Created oldest-first, on purpose, so only date ordering could produce
    // the expected sequence.
    eventOn('2025-01-10', 'earliest');
    eventOn('2025-06-01', 'middle');
    eventOn('2025-09-01', 'latest');

    $html = get('/events')->assertOk()->getContent();

    $latest = strpos($html, 'latest');
    $middle = strpos($html, 'middle');
    $earliest = strpos($html, 'earliest');

    expect($latest)->not->toBeFalse()
        ->and($middle)->not->toBeFalse()
        ->and($earliest)->not->toBeFalse()
        ->and($latest)->toBeLessThan($middle)
        ->and($middle)->toBeLessThan($earliest);
});
