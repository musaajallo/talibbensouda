<x-guest-layout>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                placeholder="your@email.com" required autofocus autocomplete="username"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>{{ __('Send Reset Link') }}</x-primary-button>

        <div class="auth-footer-link">
            <a href="{{ route('login') }}">Back to Sign In</a>
        </div>

    </form>

</x-guest-layout>
