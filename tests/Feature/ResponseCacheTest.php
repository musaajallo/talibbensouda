<?php

use App\Models\Testimonial;
use App\Models\User;
use App\Settings\AboutPageSettings;
use App\Support\ResponseCache\CachePublicPages;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    // The suite disables the response cache globally (phpunit.xml) for
    // determinism; turn it back on just for these tests, against an in-memory
    // store so nothing touches disk.
    config([
        'responsecache.enabled' => true,
        'responsecache.debug.enabled' => true,
        'responsecache.cache.store' => 'array',
    ]);
    ResponseCache::clear();
});

afterEach(fn () => ResponseCache::clear());

describe('CachePublicPages profile', function (): void {
    $profile = fn () => new CachePublicPages;
    $request = fn (string $path) => Request::create($path, 'GET');

    it('caches marketing pages', function () use ($profile, $request): void {
        expect($profile()->shouldCacheRequest($request('/')))->toBeTrue()
            ->and($profile()->shouldCacheRequest($request('/about')))->toBeTrue()
            ->and($profile()->shouldCacheRequest($request('/events')))->toBeTrue();
    });

    it('never caches the panel, the form pages or live endpoints', function () use ($profile, $request): void {
        foreach (['/admin', '/admin/login', '/contact', '/events/register', '/health-check', '/sitemap.xml'] as $path) {
            expect($profile()->shouldCacheRequest($request($path)))->toBeFalse("expected {$path} to bypass the cache");
        }
    });

    it('never caches a request from a signed-in user', function () use ($profile, $request): void {
        actingAs(User::factory()->create());

        expect($profile()->shouldCacheRequest($request('/')))->toBeFalse();
    });
});

it('serves the second request to a public page from the cache', function (): void {
    get('/')->assertOk()->assertHeader('X-Cache-Status', 'MISS');
    get('/')->assertOk()->assertHeader('X-Cache-Status', 'HIT');
});

it('keeps the CSP nonce in the body and header in sync on a cache hit', function (): void {
    $miss = get('/')->assertHeader('X-Cache-Status', 'MISS');
    $hit = get('/')->assertHeader('X-Cache-Status', 'HIT');

    preg_match('/nonce-([A-Za-z0-9]+)/', $hit->headers->get('Content-Security-Policy'), $header);
    preg_match('/<script nonce="([A-Za-z0-9]+)">document\.documentElement/', $hit->getContent(), $body);

    expect($header[1])->not->toBeEmpty()->toBe($body[1]);
});

it('does not cache the contact form page', function (): void {
    get('/contact')->assertOk()->assertHeader('X-Cache-Status', 'MISS');
    get('/contact')->assertOk()->assertHeader('X-Cache-Status', 'MISS');
});

it('flushes the cache when content changes', function (): void {
    get('/')->assertHeader('X-Cache-Status', 'MISS');
    get('/')->assertHeader('X-Cache-Status', 'HIT');

    Testimonial::create(['quote' => 'A fresh word.', 'name' => 'A Resident', 'published' => true, 'sort_order' => 1]);

    get('/')->assertHeader('X-Cache-Status', 'MISS');
});

it('flushes the cache when a settings page is saved', function (): void {
    get('/about')->assertHeader('X-Cache-Status', 'MISS');
    get('/about')->assertHeader('X-Cache-Status', 'HIT');

    app(AboutPageSettings::class)->save();

    get('/about')->assertHeader('X-Cache-Status', 'MISS');
});
