<?php

use App\Filament\Admin\Auth\RequestPasswordReset;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(fn () => seed(RolesAndPermissionsSeeder::class));

it('renders the reset-request page in the branded split layout', function (): void {
    get('/admin/password-reset/request')
        ->assertOk()
        ->assertSee('fi-split-login', false)
        ->assertSee('Reset your password')
        ->assertSee('Back to sign in');
});

it('emails a reset link to an admin', function (): void {
    Notification::fake();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::test(RequestPasswordReset::class)
        ->set('data.email', $admin->email)
        ->call('request')
        ->assertHasNoErrors();

    Notification::assertSentTo($admin, ResetPasswordNotification::class);
});

it('does not email a reset link to a non-admin account', function (): void {
    Notification::fake();

    $person = User::factory()->create(); // no panel role

    Livewire::test(RequestPasswordReset::class)
        ->set('data.email', $person->email)
        ->call('request');

    Notification::assertNothingSent();
});
