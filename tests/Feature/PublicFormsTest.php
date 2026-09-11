<?php

use App\Models\ContactMessage;
use App\Models\EventRegistration;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\RateLimiter;

use function Pest\Laravel\post;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    config(['honeypot.enabled' => false]);
    RateLimiter::clear('public-forms');
    seed(RolesAndPermissionsSeeder::class);
});

it('stores a contact message and alerts the admins', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    post('/contact', [
        'name' => 'Aji Ceesay',
        'email' => 'aji@example.com',
        'subject' => 'Volunteering',
        'message' => 'I would like to help with the campaign.',
    ])->assertRedirect();

    expect(ContactMessage::where('email', 'aji@example.com')->exists())->toBeTrue()
        ->and($admin->fresh()->unreadNotifications)->toHaveCount(1)
        ->and($admin->unreadNotifications->first()->data['title'])->toBe('New contact message');
});

it('stores an event registration and alerts the admins', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    post('/events/register', [
        'name' => 'Modou Njie',
        'email' => 'modou@example.com',
        'event' => 'Town hall — Bakau',
        'guests' => 2,
    ])->assertRedirect();

    expect(EventRegistration::where('email', 'modou@example.com')->exists())->toBeTrue()
        ->and($admin->fresh()->unreadNotifications)->toHaveCount(1);
});

it('rate-limits the public form endpoints', function (): void {
    $payload = fn (int $i) => [
        'name' => "Person {$i}",
        'email' => "person{$i}@example.com",
        'subject' => 'Hi',
        'message' => 'Hello there from a test.',
    ];

    foreach (range(1, 5) as $i) {
        post('/contact', $payload($i))->assertRedirect();
    }

    post('/contact', $payload(6))->assertStatus(429);
});
