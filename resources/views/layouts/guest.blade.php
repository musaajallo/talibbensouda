<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($pageTitle) ? $pageTitle . ' — ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/sass/frontend/frontend.scss', 'resources/js/frontend.js'])
</head>
<body>

@php
$pageInfo = match(request()->route()?->getName()) {
    'login'               => ['Sign In',          'Welcome back — enter your credentials to continue.'],
    'password.request'    => ['Reset Password',   'Enter your email and we\'ll send you a reset link.'],
    'password.reset'      => ['New Password',     'Choose a strong new password for your account.'],
    'verification.notice' => ['Verify Your Email','Check your inbox and click the verification link.'],
    'password.confirm'    => ['Confirm Password', 'Enter your password to access this secure area.'],
    'register'            => ['Create Account',   'Set up access to the campaign portal.'],
    default               => ['Portal Access',    ''],
};
@endphp

<div class="auth-shell">

    {{-- ── Left: Brand panel ── --}}
    <aside class="auth-brand">
        <a href="{{ url('/') }}" class="auth-brand__logo">Talib Bensouda</a>

        <div class="auth-brand__body">
            <p class="auth-brand__name">Talib Bensouda</p>
            <p class="auth-brand__tagline">The People's Mayor.</p>
        </div>

        <footer class="auth-brand__footer">
            <p class="auth-brand__quote">"A Gambia that works for everyone — not just the few."</p>
            <p class="auth-brand__copy">&copy; {{ date('Y') }} Talib Bensouda. Campaign Portal.</p>
        </footer>
    </aside>

    {{-- ── Right: Form panel ── --}}
    <main class="auth-form-panel">
        <div class="auth-form-wrap">

            <a href="{{ url('/') }}" class="auth-mobile-brand">Talib Bensouda</a>

            <a href="{{ url('/') }}" class="auth-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Back to website
            </a>

            <h1 class="auth-heading">{{ $pageInfo[0] }}</h1>
            @if($pageInfo[1])
                <p class="auth-subheading">{{ $pageInfo[1] }}</p>
            @endif

            {{ $slot }}

        </div>
    </main>

</div>

</body>
</html>
