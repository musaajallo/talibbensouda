<?php

use App\Models\Testimonial;
use App\Models\User;
use App\Settings\AboutPageSettings;
use App\Support\ResponseCache\CachePublicPages;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
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
        foreach (['/admin', '/admin/login', '/contact', '/events/register', '/testimonials/submit/abc', '/health-check', '/sitemap.xml'] as $path) {
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

describe('flash data from the site-wide sign-up pop-up', function (): void {
    it('bypasses the cache for the redirect-back request so the thank-you shows', function (): void {
        // Warm the cache with the plain page first — the stale copy a real
        // redirect-back GET would otherwise be answered with.
        get('/about')->assertHeader('X-Cache-Status', 'MISS');
        get('/about')->assertHeader('X-Cache-Status', 'HIT');

        $this->withSession(['campaign_signup_success' => 'Thanks — flash-canary-123'])
            ->get('/about')
            ->assertOk()
            ->assertHeaderMissing('X-Cache-Status')
            ->assertSee('flash-canary-123');
    });

    it('never stores the flashed render for the visitors who follow', function (): void {
        $this->withSession(['campaign_signup_success' => 'Thanks — flash-canary-456'])->get('/about');

        // withSession sets a plain key that would linger in the test client's
        // session (real flash data is gone after one request) — clear it so
        // this next request is a genuinely different, anonymous visitor.
        $this->flushSession();

        get('/about')->assertOk()->assertDontSee('flash-canary-456');
    });

    it('also bypasses the cache when validation errors are pending', function (): void {
        get('/about');

        $this->withSession(['errors' => (new ViewErrorBag)->put(
            'campaignSignup',
            new MessageBag(['popup_phone' => 'error-canary-789']),
        )])->get('/about')->assertOk()->assertHeaderMissing('X-Cache-Status');
    });
});

describe('asset-build fingerprint in the cache key', function (): void {
    // Point public_path() at a scratch dir so a "deploy" is just rewriting the manifest.
    beforeEach(function (): void {
        $this->publicDir = sys_get_temp_dir().'/rc-build-'.uniqid();
        mkdir($this->publicDir.'/build', 0777, true);
        app()->usePublicPath($this->publicDir);
    });

    afterEach(function (): void {
        app()->usePublicPath(base_path('public'));
        @unlink($this->publicDir.'/build/manifest.json');
        @rmdir($this->publicDir.'/build');
        @rmdir($this->publicDir);
    });

    $deploy = fn (string $manifest) => file_put_contents(public_path('build/manifest.json'), $manifest);

    it('never serves a page cached by an older build to a newer one', function () use ($deploy): void {
        // Release N caches /about …
        $deploy('{"home.css":"assets/home-OLDHASH.css"}');
        get('/about')->assertHeader('X-Cache-Status', 'MISS');
        get('/about')->assertHeader('X-Cache-Status', 'HIT');

        // … release N+1 ships new hashed filenames. It must render its own page,
        // not replay the old one (whose CSS/JS files no longer exist).
        $deploy('{"home.css":"assets/home-NEWHASH.css"}');
        get('/about')->assertHeader('X-Cache-Status', 'MISS');
        get('/about')->assertHeader('X-Cache-Status', 'HIT');
    });

    it('keeps each build\'s entries apart, so a rollback finds its own again', function () use ($deploy): void {
        $deploy('{"v":1}');
        get('/about');
        $deploy('{"v":2}');
        get('/about');

        $deploy('{"v":1}');

        get('/about')->assertHeader('X-Cache-Status', 'HIT');
    });

    it('is stable while the build is unchanged and changes with it', function () use ($deploy): void {
        $deploy('{"v":1}');
        $a = CachePublicPages::buildFingerprint();
        expect(CachePublicPages::buildFingerprint())->toBe($a);

        $deploy('{"v":2}');
        expect(CachePublicPages::buildFingerprint())->not->toBe($a);
    });

    it('has no build to hash when there is no manifest, yet still tells releases apart', function (): void {
        // Two release directories with no manifest are still different deploys.
        expect(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-1'))
            ->not->toBe(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-2'))
            ->and(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-1'))
            ->toBe(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-1'));
    });

    it('tells a Blade-only deploy apart: same assets, different release directory', function () use ($deploy): void {
        $deploy('{"home.css":"assets/home-SAME.css"}'); // identical build output …

        expect(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-1'))
            ->not->toBe(CachePublicPages::buildFingerprint('/srv/site/releases/20260919-2')); // … new release
    });
});
