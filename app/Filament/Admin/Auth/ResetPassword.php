<?php

namespace App\Filament\Admin\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Illuminate\Contracts\Support\Htmlable;

class ResetPassword extends BaseResetPassword
{
    protected static string $layout = 'filament.admin.auth.split-layout';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Choose a new password';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Pick something you don\'t use anywhere else.';
    }
}
