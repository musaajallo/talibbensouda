<?php

use App\Models\User;
use App\Settings\GeneralSettings;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('does not gate the site when maintenance mode is off', function (): void {
    get('/')->assertOk()->assertDontSee('Be right back', false);
});

it('shows a 503 maintenance page to anonymous visitors when enabled', function (): void {
    $settings = app(GeneralSettings::class);
    $settings->maintenance_mode = true;
    $settings->save();

    get('/')
        ->assertStatus(503)
        ->assertHeader('Retry-After', '3600')
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
        ->assertSee('Be right back', false);
});

it('lets a signed-in user through during maintenance mode', function (): void {
    $settings = app(GeneralSettings::class);
    $settings->maintenance_mode = true;
    $settings->save();

    actingAs(User::factory()->create());

    get('/')->assertOk();
});

it('still allows the admin login page during maintenance mode', function (): void {
    $settings = app(GeneralSettings::class);
    $settings->maintenance_mode = true;
    $settings->save();

    get('/admin/login')->assertOk();
});

it('bypasses the gate for requests carrying the X-Livewire header', function (): void {
    // Filament's login form posts through Livewire's update endpoint, whose
    // path is a random per-app hash rather than a fixed /livewire/* prefix
    // -- Livewire tags these requests with an X-Livewire header instead, so
    // that's what the middleware checks. A 404 here (route not found) means
    // the request reached the router instead of being stopped by the gate;
    // a 503 would mean the bypass isn't working.
    $settings = app(GeneralSettings::class);
    $settings->maintenance_mode = true;
    $settings->save();

    $this->withHeaders(['X-Livewire' => 'true'])
        ->post('/some-random-livewire-update-path')
        ->assertStatus(404);
});

it('still gates a plain request to a different path while maintenance mode is on', function (): void {
    $settings = app(GeneralSettings::class);
    $settings->maintenance_mode = true;
    $settings->save();

    get('/contact')->assertStatus(503);
});
