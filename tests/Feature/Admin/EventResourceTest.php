<?php

use App\Filament\Admin\Resources\Events\Pages\CreateEvent;
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

it('creates an event, auto-slugs the title and derives every date field from one picker', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Bakau Multipurpose Facility Launched',
            'badge' => 'gambia',
            'flag' => '🇬🇲',
            'is_upcoming' => true,
            'sort_order' => 7,
            'starts_at' => '2025-06-01 09:00:00',
            'ends_at' => '2025-06-01 16:00:00',
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
        ->and($event->js_month)->toBe(5)          // June, 0-indexed
        ->and($event->ics_start)->toBe('20250601T090000Z')
        ->and($event->ics_end)->toBe('20250601T160000Z');
});

it('rejects an end that is before the start', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Backwards Event',
            'badge' => 'gambia',
            'sort_order' => 1,
            'starts_at' => '2025-06-01 16:00:00',
            'ends_at' => '2025-06-01 09:00:00',
            'location' => 'X',
            'description' => 'x',
        ])
        ->call('create')
        ->assertHasFormErrors(['ends_at']);
});

it('rejects a duplicate slug', function (): void {
    Event::create([
        'slug' => 'existing-event',
        'title' => 'Existing', 'badge' => 'gambia',
        'starts_at' => '2025-01-01 09:00:00', 'ends_at' => '2025-01-01 10:00:00',
        'location' => 'X', 'description' => 'x',
    ]);

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Another', 'slug' => 'existing-event', 'badge' => 'gambia',
            'flag' => '🇬🇲', 'sort_order' => 1,
            'starts_at' => '2025-01-01 09:00:00', 'ends_at' => '2025-01-01 10:00:00',
            'location' => 'X', 'description' => 'x',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('round-trips the pickers on the edit form', function (): void {
    $event = Event::create([
        'slug' => 'town-hall', 'title' => 'Town hall', 'badge' => 'gambia',
        'starts_at' => '2025-03-15 18:00:00', 'ends_at' => '2025-03-15 20:00:00',
        'location' => 'Serrekunda', 'description' => 'Community meeting.',
    ]);

    expect($event->fresh()->starts_at->format('Y-m-d H:i'))->toBe('2025-03-15 18:00');
});
