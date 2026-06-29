<x-guest-layout>
    <x-honeypot />

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="form-group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')"
                placeholder="Your full name" required autofocus autocomplete="name"
                class="{{ $errors->has('name') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                placeholder="your@email.com" required autocomplete="username"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                placeholder="At least 8 characters" required autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                placeholder="Repeat your password" required autocomplete="new-password"
                class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>{{ __('Create Account') }}</x-primary-button>

        <div class="auth-footer-link">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>

    </form>

</x-guest-layout>
