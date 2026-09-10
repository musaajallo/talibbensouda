<?php

namespace App\Filament\Admin\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Contracts\Support\Htmlable;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    protected static string $layout = 'filament.admin.auth.split-layout';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Reset your password';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Enter the email for your admin account and we\'ll send a reset link.';
    }
}
