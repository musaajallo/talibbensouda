<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);
});

function superAdmin(): User
{
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    return $user;
}

it('redirects guests to the panel login', function (): void {
    get('/admin')->assertRedirect('/admin/login');
});

it('forbids signed-in users without an admin role', function (): void {
    actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('lets a super-admin reach the panel and its pages', function (string $path): void {
    actingAs(superAdmin())
        ->get($path)
        ->assertSuccessful();
})->with([
    '/admin',
    '/admin/events',
    '/admin/gallery-photos',
    '/admin/gallery-photos/create',
    '/admin/projects',
    '/admin/projects/create',
    '/admin/testimonials',
    '/admin/testimonials/create',
    '/admin/community-photos',
    '/admin/community-photos/create',
    '/admin/giving-programmes',
    '/admin/giving-programmes/create',
    '/admin/contact-messages',
    '/admin/event-registrations',
    '/admin/users',
    '/admin/manage-general-settings',
    '/admin/manage-social-settings',
    '/admin/manage-site-chrome',
    '/admin/manage-home-page',
    '/admin/manage-peoples-mayor-page',
    '/admin/manage-giving-back-page',
    '/admin/manage-about-page',
    '/admin/manage-events-page',
    '/admin/shield/roles',
]);
