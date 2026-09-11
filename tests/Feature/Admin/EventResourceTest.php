<?php

use App\Filament\Admin\Resources\Events\Pages\CreateEvent;
use App\Filament\Admin\Resources\Events\Pages\EditEvent;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);
});

/** Create an Event straight in the DB with the columns the seeder uses. */
function eventRow(array $overrides = []): Event
{
    return Event::create(array_merge([
        'slug' => 'existing-event',
        'title' => 'Existing',
        'badge' => 'gambia',
        'date_day' => '1', 'date_month' => 'Jan', 'date_year' => '2025',
        'js_day' => 1, 'js_month' => 0,
        'location' => 'X', 'description' => 'x',
        'ics_start' => '20250101T090000Z', 'ics_end' => '20250101T100000Z',
    ], $overrides));
}

it('creates an event, auto-slugs the title and derives every date column from one picker + two times', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Bakau Multipurpose Facility Launched',
            'badge' => 'gambia',
            'flag' => '🇬🇲',
            'is_upcoming' => true,
            'sort_order' => 7,
            'event_date' => '2025-06-01',
            'start_time' => '09:00',
            'end_time' => '16:00',
            'location' => 'Bakau',
            'description' => 'Launch of a community facility.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $event = Event::whereSlug('bakau-multipurpose-facility-launched')->firstOrFail();

    expect($event->date_day)->toBe('1')
        ->and($event->date_month)->toBe('Jun')
        ->and($event->date_year)->toBe('2025')
        ->and($event->js_day)->toBe(1)
        ->and($event->js_month)->toBe(5)              // June, 0-indexed
        ->and($event->ics_start)->toBe('20250601T090000Z')
        ->and($event->ics_end)->toBe('20250601T160000Z');
});

it('defaults the times when only a date is given', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Date only',
            'badge' => 'gambia',
            'sort_order' => 1,
            'event_date' => '2025-07-04',
            'location' => 'Serrekunda',
            'description' => 'x',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $event = Event::whereSlug('date-only')->firstOrFail();

    expect($event->ics_start)->toBe('20250704T090000Z')
        ->and($event->ics_end)->toBe('20250704T170000Z');
});

it('rejects a duplicate slug', function (): void {
    eventRow();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Another', 'slug' => 'existing-event', 'badge' => 'gambia',
            'flag' => '🇬🇲', 'sort_order' => 1,
            'event_date' => '2025-01-01', 'start_time' => '09:00', 'end_time' => '10:00',
            'location' => 'X', 'description' => 'x',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('pre-fills the date and time pickers from the stored ICS timestamps', function (): void {
    $event = eventRow([
        'slug' => 'town-hall',
        'ics_start' => '20250315T180000Z',
        'ics_end' => '20250315T200000Z',
    ]);

    Livewire::test(EditEvent::class, ['record' => $event->slug])
        ->assertFormSet([
            'event_date' => '2025-03-15',
            'start_time' => '18:00',
            'end_time' => '20:00',
        ]);
});

it('stores the rich write-up as HTML', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Rich event', 'badge' => 'gambia', 'sort_order' => 1,
            'event_date' => '2025-05-01',
            'location' => 'Kanifing', 'description' => 'x',
            'full_description' => '<p>First paragraph.</p><p>Second.</p>',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Event::whereSlug('rich-event')->value('full_description'))
        ->toBe('<p>First paragraph.</p><p>Second.</p>');
});
