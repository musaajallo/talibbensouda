<?php

use App\Settings\SocialSettings;

use function Pest\Laravel\get;

it('renders the core SEO tags on every page', function (string $url): void {
    $res = get($url)->assertOk();

    $res->assertSee('<meta name="description"', false)
        ->assertSee('<link rel="canonical" href="'.url($url).'"', false)
        ->assertSee('property="og:title"', false)
        ->assertSee('property="og:image"', false)
        ->assertSee('name="twitter:card" content="summary_large_image"', false);
})->with(['/', '/about', '/events', '/giving-back', '/contact']);

it('uses the per-page description when one is set', function (): void {
    get('/about')->assertSee('from Kanifing Municipal Council to a national vision', false);
});

it('emits a valid JSON-LD graph with a Person and a WebSite', function (): void {
    $html = get('/')->assertOk()->getContent();

    expect($html)->toMatch('#<script type="application/ld\+json">(.+?)</script>#');

    preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $m);
    $data = json_decode($m[1], true, flags: JSON_THROW_ON_ERROR);

    $types = collect($data['@graph'])->pluck('@type');

    expect($types)->toContain('Person')->toContain('WebSite');
});

it('puts the Twitter handle in the card when X is configured', function (): void {
    app(SocialSettings::class)->fill(['x' => 'https://x.com/MayorBensouda'])->save();

    get('/')->assertSee('name="twitter:site" content="@MayorBensouda"', false);
});

it('serves a robots.txt that points at the sitemap and blocks the panel', function (): void {
    $body = file_get_contents(public_path('robots.txt'));

    expect($body)
        ->toContain('Disallow: /admin')
        ->toContain('Sitemap: https://talibahmedbensouda.com/sitemap.xml')
        ->not->toContain('talibbensouda.gm');
});
