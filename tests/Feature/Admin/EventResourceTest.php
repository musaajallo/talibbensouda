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

it('creates an event and auto-slugs the title', function (): void {
    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Bakau Multipurpose Facility Launched',
            'badge' => 'gambia',
            'flag' => '🇬🇲',
            'is_upcoming' => true,
            'sort_order' => 7,
            'date_day' => '01',
            'date_month' => 'Jun',
            'date_year' => '2025',
            'js_day' => 1,
            'js_month' => 5,
            'location' => 'Bakau',
            'description' => 'Launch of a community facility.',
            'ics_start' => '20250601T090000Z',
            'ics_end' => '20250601T160000Z',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Event::whereSlug('bakau-multipurpose-facility-launched')->exists())->toBeTrue();
});

it('rejects a duplicate slug', function (): void {
    Event::create([
        'slug' => 'existing-event',
        'title' => 'Existing', 'badge' => 'gambia',
        'date_day' => '1', 'date_month' => 'Jan', 'date_year' => '2025',
        'js_day' => 1, 'js_month' => 0, 'location' => 'X',
        'description' => 'x', 'ics_start' => '20250101T090000Z', 'ics_end' => '20250101T100000Z',
    ]);

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Another', 'slug' => 'existing-event', 'badge' => 'gambia',
            'flag' => '🇬🇲', 'sort_order' => 1,
            'date_day' => '1', 'date_month' => 'Jan', 'date_year' => '2025',
            'js_day' => 1, 'js_month' => 0, 'location' => 'X',
            'description' => 'x', 'ics_start' => '20250101T090000Z', 'ics_end' => '20250101T100000Z',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});
