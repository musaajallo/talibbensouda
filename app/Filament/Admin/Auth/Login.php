<?php

namespace App\Filament\Admin\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.admin.auth.split-layout';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getHeading();
        }

        return 'Admin sign in';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        return 'Manage events, projects and page content for '.config('app.name').'.';
    }

    protected function throwFailureValidationException(): never
    {
        // Surface the credentials error as a banner above the form (rendered
        // by the AUTH_LOGIN_FORM_BEFORE hook) rather than as a field-level
        // error under the email input. The flash is consumed on the next
        // Livewire request, so the banner clears as soon as the user types.
        session()->flash('admin_login_error', 'Email or password is wrong.');

        throw ValidationException::withMessages([
            'authenticationFailure' => 'Email or password is wrong.',
        ]);
    }
}
