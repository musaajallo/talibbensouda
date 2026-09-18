<?php

use App\Models\CampaignSignup;
use App\Settings\SiteChromeSettings;
use Database\Seeders\RolesAndPermissionsSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    // CampaignSignupObserver notifies admins via AdminAlert, which needs the
    // admin/super-admin roles to exist — same requirement as any other test
    // that submits a form backed by that observer pattern.
    seed(RolesAndPermissionsSeeder::class);
});

it('creates a signup with only the required fields', function (): void {
    post(route('campaign-signup.store'), [
        'popup_name' => 'Jane Doe',
        'popup_phone' => '+2201234567',
    ])->assertRedirect()->assertSessionHas('campaign_signup_success');

    $signup = CampaignSignup::first();

    expect($signup)->not->toBeNull()
        ->and($signup->name)->toBe('Jane Doe')
        ->and($signup->phone)->toBe('+2201234567')
        ->and($signup->location)->toBeNull()
        ->and($signup->wants_updates)->toBeFalse();
});

it('records the optional location and the consent checkbox when given', function (): void {
    post(route('campaign-signup.store'), [
        'popup_name' => 'Jane Doe',
        'popup_phone' => '+2201234567',
        'popup_location' => 'Bakau',
        'popup_wants_updates' => '1',
    ])->assertRedirect();

    $signup = CampaignSignup::first();

    expect($signup->location)->toBe('Bakau')
        ->and($signup->wants_updates)->toBeTrue();
});

it('requires a name and phone number', function (): void {
    post(route('campaign-signup.store'), [])
        ->assertSessionHasErrorsIn('campaignSignup', ['popup_name', 'popup_phone']);

    expect(CampaignSignup::count())->toBe(0);
});

it('shows the pop-up markup on a public page, with the consent box unchecked by default', function (): void {
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('signup-popup')
        ->and($html)->toContain('popup_wants_updates')
        ->and($html)->not->toContain('name="popup_wants_updates" value="1" checked');
});

it('hides the pop-up entirely when disabled in settings', function (): void {
    $chrome = app(SiteChromeSettings::class);
    $chrome->signup_popup_enabled = false;
    $chrome->save();

    get('/')->assertOk()->assertDontSee('signup-popup', false);
});

it('shows the success message after a real submission', function (): void {
    post(route('campaign-signup.store'), [
        'popup_name' => 'Jane Doe',
        'popup_phone' => '+2201234567',
    ]);

    get('/')->assertOk()->assertSee('Thanks, Jane Doe');
});

it('words validation errors for the visitor, not after the popup_* field names', function (): void {
    post(route('campaign-signup.store'), [])
        ->assertSessionHasErrorsIn('campaignSignup', [
            'popup_name' => 'The full name field is required.',
            'popup_phone' => 'The phone number field is required.',
        ]);
});

it('defaults the pop-up to two minutes of browsing before it can appear', function (): void {
    expect(app(SiteChromeSettings::class)->signup_popup_delay_seconds)->toBe(120);

    get('/')->assertOk()->assertSee('delaySeconds: 120', false);
});

it('renders whatever delay is set in the panel into the page', function (): void {
    $chrome = app(SiteChromeSettings::class);
    $chrome->signup_popup_delay_seconds = 45;
    $chrome->save();

    get('/')->assertOk()->assertSee('delaySeconds: 45', false);
});

it('never renders a negative delay', function (): void {
    $chrome = app(SiteChromeSettings::class);
    $chrome->signup_popup_delay_seconds = -30;
    $chrome->save();

    get('/')->assertOk()->assertSee('delaySeconds: 0', false);
});

it('defaults a dismissed pop-up to a two-week snooze', function (): void {
    expect(app(SiteChromeSettings::class)->signup_popup_snooze_days)->toBe(14);

    get('/')->assertOk()->assertSee('snoozeDays: 14', false);
});

it('renders the snooze length set in the panel, and never a negative one', function (): void {
    $chrome = app(SiteChromeSettings::class);
    $chrome->signup_popup_snooze_days = 30;
    $chrome->save();

    get('/')->assertOk()->assertSee('snoozeDays: 30', false);

    $chrome->signup_popup_snooze_days = -3;
    $chrome->save();

    get('/')->assertOk()->assertSee('snoozeDays: 0', false);
});
