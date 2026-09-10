<?php

use App\Settings\SiteChromeSettings;
use App\Settings\SocialSettings;

use function Pest\Laravel\get;

it('renders the Join Party CTA from settings', function (): void {
    $chrome = app(SiteChromeSettings::class);
    $chrome->join_party_label = 'Become a member';
    $chrome->join_party_url = 'https://example.test/members';
    $chrome->save();

    get('/')
        ->assertOk()
        ->assertSee('Become a member')
        ->assertSee('https://example.test/members');
});

it('builds the page title as "<site name> — <page>"', function (): void {
    get('/')->assertOk()->assertSee('<title>Talib Bensouda</title>', false);
    get('/about')->assertOk()->assertSee('<title>Talib Bensouda — About</title>', false);
    get('/contact')->assertOk()->assertSee('<title>Talib Bensouda — Get in Touch</title>', false);
});

it('links the SVG favicon and web manifest', function (): void {
    get('/')
        ->assertOk()
        ->assertSee('favicon.svg', false)
        ->assertSee('rel="manifest"', false)
        ->assertSee('name="theme-color"', false);
});

it('hides social icons that have no URL set', function (): void {
    $social = app(SocialSettings::class);
    $social->facebook = 'https://facebook.com/example';
    $social->x = null;
    $social->instagram = null;
    $social->youtube = null;
    $social->whatsapp = null;
    $social->save();

    $response = get('/contact')->assertOk();

    $response->assertSee('https://facebook.com/example');
    $response->assertDontSee('aria-label="Instagram"', escape: false);
});
