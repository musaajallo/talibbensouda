<?php

use App\Models\Event;
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
