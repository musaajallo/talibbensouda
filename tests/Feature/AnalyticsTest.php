<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Fomvasss\Visits\Models\Event as VisitEvent;
use Fomvasss\Visits\Models\Visitor;
use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Laravel\withUnencryptedCookie;

it('does not track a visitor who has not consented to analytics', function (): void {
    get('/')->assertOk();

    expect(Visitor::count())->toBe(0)
        ->and(VisitEvent::count())->toBe(0);
});

it('tracks a page view once the analytics consent cookie is present', function (): void {
    // Plain (unencrypted) on purpose — this is exactly how the browser sends
    // it, since the cookie banner sets it with plain `document.cookie`, not a
    // Laravel-encrypted one.
    withUnencryptedCookie('tb_analytics_consent', '1')->get('/')->assertOk();

    expect(Visitor::count())->toBe(1)
        ->and(VisitEvent::count())->toBe(1);
});

it('still tracks a page view served from the response cache', function (): void {
    // The suite disables the response cache globally (phpunit.xml) for
    // determinism; turn it back on just for this test, against an in-memory
    // store — see ResponseCacheTest for the same pattern.
    config([
        'responsecache.enabled' => true,
        'responsecache.debug.enabled' => true,
        'responsecache.cache.store' => 'array',
    ]);
    ResponseCache::clear();

    withUnencryptedCookie('tb_analytics_consent', '1')->get('/')->assertOk()->assertHeader('X-Cache-Status', 'MISS');
    withUnencryptedCookie('tb_analytics_consent', '1')->get('/')->assertOk()->assertHeader('X-Cache-Status', 'HIT');

    // 'track-visits' sits ahead of CacheResponse in the `web` group precisely
    // so a cache hit — most real traffic, once the cache is warm — still gets
    // counted instead of silently vanishing from the numbers.
    expect(VisitEvent::count())->toBe(2);

    ResponseCache::clear();
});

it('redirects a guest away from the analytics dashboard', function (): void {
    get('/analytics')->assertRedirect('/admin/login');
});

it('forbids a signed-in user without an admin role from the analytics dashboard', function (): void {
    actingAs(User::factory()->create())->get('/analytics')->assertForbidden();
});

it('lets an admin reach the analytics dashboard', function (): void {
    seed(RolesAndPermissionsSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    actingAs($admin)->get('/analytics')->assertSuccessful();
});
