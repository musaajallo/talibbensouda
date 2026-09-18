<?php

use App\Models\Event;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;

function flyerlessEvent(): Event
{
    // A few days out, on purpose — the events page only gives the flyer
    // treatment to genuinely upcoming events, and only within a week of
    // happening (see EventController::index); a past-dated fixture would
    // land in the compact "Past Events" row instead (no flyer), and
    // anything further than a week out isn't shown at all.
    $date = now()->addDays(3);

    return Event::create([
        'slug' => 'no-flyer-event',
        'title' => 'Groundbreaking at Bakau',
        'badge' => 'Kanifing',
        'date_day' => $date->format('j'), 'date_month' => $date->format('M'), 'date_year' => $date->format('Y'),
        'js_day' => (int) $date->format('j'), 'js_month' => (int) $date->format('n') - 1,
        'location' => 'Bakau, Kanifing Municipality',
        'description' => 'A groundbreaking ceremony.',
        'ics_start' => $date->format('Ymd').'T090000Z', 'ics_end' => $date->format('Ymd').'T110000Z',
        'is_upcoming' => true,
    ]);
}

it('falls back to the generated placeholder when no flyer is uploaded', function (): void {
    $event = flyerlessEvent();

    expect($event->flyerUrl())->toBeNull()
        ->and($event->flyerImageUrl())->toBe(route('events.flyer-placeholder', $event));
});

it('serves a valid, on-brand SVG placeholder', function (): void {
    $event = flyerlessEvent();

    $response = get(route('events.flyer-placeholder', $event))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/svg+xml');

    $svg = $response->getContent();

    expect(simplexml_load_string($svg))->not->toBeFalse()
        ->and($svg)->toContain($event->title)
        ->and($svg)->toContain(mb_strtoupper($event->badge));
});

it('never overflows the flyer frame with an unbroken long word in the title', function (): void {
    $event = flyerlessEvent();
    $event->update(['title' => str_repeat('Supercalifragilisticexpialidocious', 3)]);

    $svg = get(route('events.flyer-placeholder', $event))->assertOk()->getContent();

    expect(simplexml_load_string($svg))->not->toBeFalse();
});

it('uses the uploaded flyer instead of the placeholder once one exists', function (): void {
    Storage::fake('public');

    $event = flyerlessEvent();
    $event->addMedia(UploadedFile::fake()->image('poster.jpg', 1200, 1500))
        ->toMediaCollection('flyer');

    expect($event->flyerUrl())->not->toBeNull()
        ->and($event->flyerImageUrl())->toBe($event->flyerUrl())
        ->and($event->flyerImageUrl())->not->toBe(route('events.flyer-placeholder', $event));
});

it('shows the flyer image on the events list and the event page', function (): void {
    $event = flyerlessEvent();

    get('/events')->assertOk()->assertSee($event->flyerThumbImageUrl(), false);

    get(route('events.show', $event))
        ->assertOk()
        ->assertSee('event-flyer', false)
        ->assertSee($event->flyerImageUrl(), false);
});

it('serves the small thumb conversion on the events list, not the full poster', function (): void {
    Storage::fake('public');

    $event = flyerlessEvent();
    $event->addMedia(UploadedFile::fake()->image('poster.jpg', 1200, 1500))
        ->toMediaCollection('flyer');

    expect($event->flyerThumbUrl())->not->toBeNull()
        ->and($event->flyerThumbUrl())->not->toBe($event->flyerUrl());

    get('/events')->assertOk()->assertSee($event->flyerThumbUrl(), false)->assertDontSee($event->flyerUrl(), false);
});
