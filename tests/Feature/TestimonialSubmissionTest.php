<?php

use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

it('shows the submission form for a valid invite token', function (): void {
    $testimonial = Testimonial::createInvite('Jane Doe', 'jane@example.com');

    get(route('testimonials.submit', $testimonial->invite_token))
        ->assertOk()
        ->assertSee('Your Testimonial');
});

it('404s for an unknown token', function (): void {
    get('/testimonials/submit/does-not-exist')->assertNotFound();
});

it('creates the content on first submission and notifies admins', function (): void {
    seed(RolesAndPermissionsSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $testimonial = Testimonial::createInvite('Jane Doe', 'jane@example.com');

    post(route('testimonials.submit.store', $testimonial->invite_token), [
        'name' => 'Jane Doe',
        'role' => 'United States · 2026',
        'quote' => 'A genuinely transformative mayor.',
    ])->assertRedirect(route('testimonials.submit', $testimonial->invite_token));

    $testimonial->refresh();

    expect($testimonial->quote)->toBe('A genuinely transformative mayor.')
        ->and($testimonial->role)->toBe('United States · 2026')
        ->and($testimonial->submitted_at)->not->toBeNull()
        ->and($testimonial->approved)->toBeFalse()
        ->and($testimonial->published)->toBeFalse();

    expect($admin->notifications()->count())->toBe(1);
});

it('lets the submitter edit their testimonial again before it is approved, without re-notifying', function (): void {
    seed(RolesAndPermissionsSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $testimonial = Testimonial::createInvite('Jane Doe', 'jane@example.com');

    post(route('testimonials.submit.store', $testimonial->invite_token), [
        'name' => 'Jane Doe', 'quote' => 'First draft.',
    ]);

    $firstSubmittedAt = $testimonial->refresh()->submitted_at;

    post(route('testimonials.submit.store', $testimonial->invite_token), [
        'name' => 'Jane Doe', 'quote' => 'Revised wording.',
    ]);

    expect($testimonial->refresh()->quote)->toBe('Revised wording.')
        ->and($firstSubmittedAt)->not->toBeNull()
        // Only the first submission should have raised an admin notification.
        ->and($admin->notifications()->count())->toBe(1);
});

it('locks editing once a submission has been approved', function (): void {
    $testimonial = Testimonial::createInvite('Jane Doe', 'jane@example.com');
    $testimonial->update(['quote' => 'Approved wording.', 'submitted_at' => now(), 'approved' => true]);

    get(route('testimonials.submit', $testimonial->invite_token))
        ->assertOk()
        ->assertSee('Thank You')
        ->assertDontSee('<textarea', false);

    post(route('testimonials.submit.store', $testimonial->invite_token), [
        'name' => 'Jane Doe', 'quote' => 'Trying to sneak in a change.',
    ])->assertRedirect(route('testimonials.submit', $testimonial->invite_token));

    expect($testimonial->refresh()->quote)->toBe('Approved wording.');
});
