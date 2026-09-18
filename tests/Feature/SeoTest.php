<?php

use App\Models\Event;
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

it('emits Event and BreadcrumbList JSON-LD on an event page', function (): void {
    $event = Event::create([
        'slug' => 'town-hall-2027',
        'title' => 'Town Hall 2027',
        'badge' => 'Kanifing',
        'date_day' => '01', 'date_month' => 'Jan', 'date_year' => '2027',
        'js_day' => 1, 'js_month' => 0,
        'location' => 'Kanifing Municipality',
        'venue' => 'Kanifing Municipal Council',
        'description' => 'An event.',
        'ics_start' => '20270101T090000Z', 'ics_end' => '20270101T100000Z',
        'is_upcoming' => true,
    ]);

    $html = get(route('events.show', $event))->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.+?)</script>#s', $html, $matches);
    $schemas = collect($matches[1])->map(fn (string $json) => json_decode($json, true, flags: JSON_THROW_ON_ERROR));

    $eventSchema = $schemas->firstWhere('@type', 'Event');
    expect($eventSchema)->not->toBeNull()
        ->and($eventSchema['name'])->toBe('Town Hall 2027')
        ->and($eventSchema['startDate'])->toBe('2027-01-01T09:00:00+00:00')
        ->and($eventSchema['location']['name'])->toBe('Kanifing Municipal Council');

    $breadcrumbSchema = $schemas->firstWhere('@type', 'BreadcrumbList');
    expect($breadcrumbSchema)->not->toBeNull()
        ->and(collect($breadcrumbSchema['itemListElement'])->pluck('name')->all())
        ->toBe(['Home', 'Events', 'Town Hall 2027']);
});

it('includes events in the sitemap with a lastmod date', function (): void {
    $event = Event::create([
        'slug' => 'town-hall-2027',
        'title' => 'Town Hall 2027',
        'badge' => 'Kanifing',
        'date_day' => '01', 'date_month' => 'Jan', 'date_year' => '2027',
        'js_day' => 1, 'js_month' => 0,
        'location' => 'Kanifing Municipality',
        'description' => 'An event.',
        'ics_start' => '20270101T090000Z', 'ics_end' => '20270101T100000Z',
        'is_upcoming' => true,
    ]);

    $body = get('/sitemap.xml')->assertOk()->getContent();

    expect($body)
        ->toContain(route('events.show', $event))
        ->toContain('<lastmod>'.$event->updated_at->toAtomString().'</lastmod>');
});

it('ships no client-side analytics beacon — page-view tracking is server-side only', function (): void {
    get('/')->assertDontSee('data-domain', false)->assertDontSee('plausible', false);
});
