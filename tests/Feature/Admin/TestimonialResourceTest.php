<?php

use App\Filament\Admin\Resources\Testimonials\Pages\ListTestimonials;
use App\Mail\TestimonialInviteMail;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);
});

it('sends a testimonial invite and creates a draft row', function (): void {
    Mail::fake();

    Livewire::test(ListTestimonials::class)
        ->mountAction('sendInvite')
        ->setActionData(['name' => 'Jane Doe', 'email' => 'jane@example.com'])
        ->callMountedAction()
        ->assertHasNoActionErrors();

    $testimonial = Testimonial::firstWhere('invite_email', 'jane@example.com');

    expect($testimonial)->not->toBeNull()
        ->and($testimonial->invite_name)->toBe('Jane Doe')
        ->and($testimonial->invite_token)->not->toBeNull()
        ->and($testimonial->published)->toBeFalse()
        ->and($testimonial->approved)->toBeFalse();

    Mail::assertQueued(TestimonialInviteMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
});

it('approves a submitted testimonial without publishing it', function (): void {
    $testimonial = Testimonial::createInvite('Jane Doe', 'jane@example.com');
    $testimonial->update(['quote' => 'A great mayor.', 'submitted_at' => now()]);

    Livewire::test(ListTestimonials::class)
        ->callTableAction('approve', $testimonial);

    $testimonial->refresh();

    expect($testimonial->approved)->toBeTrue()
        ->and($testimonial->published)->toBeFalse();
});
