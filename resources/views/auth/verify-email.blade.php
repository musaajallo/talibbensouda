<x-guest-layout>

    <x-auth-session-status :status="session('status') === 'verification-link-sent' ? __('A fresh verification link has been sent to your email.') : null" />

    <p class="auth-verify-note">
        Thanks for signing up. Before you continue, please verify your email address
        by clicking the link we sent you. Check your spam folder if you don't see it.
    </p>

    <div class="auth-actions">

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn--outline-navy">{{ __('Sign Out') }}</button>
        </form>

    </div>

</x-guest-layout>
