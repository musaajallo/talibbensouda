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

    Mail::assertSent(TestimonialInviteMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
});

it('surfaces a loud error and rolls back the draft row when sending fails', function (): void {
    // Force a real, fast transport failure (connection refused) rather than
    // mocking — this is exactly the class of failure the try/catch needs to
    // catch instead of letting it fail silently.
    config([
        'mail.default' => 'smtp',
        'mail.mailers.smtp.host' => '127.0.0.1',
        'mail.mailers.smtp.port' => 1,
        'mail.mailers.smtp.timeout' => 2,
    ]);

    Livewire::test(ListTestimonials::class)
        ->mountAction('sendInvite')
        ->setActionData(['name' => 'Jane Doe', 'email' => 'jane@example.com'])
        ->callMountedAction();

    expect(Testimonial::where('invite_email', 'jane@example.com')->exists())->toBeFalse();
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
