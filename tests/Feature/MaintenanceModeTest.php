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
