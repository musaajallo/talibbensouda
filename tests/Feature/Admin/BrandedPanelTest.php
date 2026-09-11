<?php

use App\Filament\Admin\Auth\Login;
use App\Filament\Admin\Widgets\ContentOverview;
use App\Filament\Admin\Widgets\LatestContactMessages;
use App\Filament\Admin\Widgets\QuickActions;
use App\Filament\Admin\Widgets\SubmissionsOverview;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);
});

it('renders the branded split-screen login', function (): void {
    get('/admin/login')
        ->assertSuccessful()
        ->assertSee('fi-split-login', false)
        ->assertSee('Admin sign in')
        ->assertSee("The People's Mayor.", false)
        ->assertSee('Back to site');
});

it('reports wrong credentials without an inline email field error', function (): void {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'not-the-password')
        ->call('authenticate')
        ->assertHasErrors('authenticationFailure')
        ->assertHasNoErrors('data.email');
});

it('renders the dashboard for an admin with all widgets registered', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    actingAs($admin)->get('/admin')->assertSuccessful();
});

it('populates the dashboard widgets from live data', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);

    Event::create([
        'slug' => 'town-hall', 'title' => 'Town hall', 'badge' => 'Kanifing',
        'date_day' => '01', 'date_month' => 'Jan', 'date_year' => '2027',
        'js_day' => 1, 'js_month' => 0, 'location' => 'Kanifing',
        'description' => 'Quarterly town hall.', 'ics_start' => '20270101T090000Z',
        'ics_end' => '20270101T100000Z', 'is_upcoming' => true,
    ]);
    ContactMessage::create([
        'type' => 'inquiry', 'name' => 'Jane Njie', 'email' => 'jane@example.com', 'message' => 'Hi',
    ]);

    Livewire::test(QuickActions::class)->assertSee('Quick actions');
    Livewire::test(SubmissionsOverview::class)
        ->assertSee('Messages this week')
        ->assertSee('Upcoming events');
    Livewire::test(ContentOverview::class)->assertSee('Projects');
    Livewire::test(LatestContactMessages::class)
        ->assertSee('Latest messages')
        ->assertSee('Jane Njie');
});
