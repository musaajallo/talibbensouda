<x-guest-layout>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                placeholder="your@email.com" required autofocus autocomplete="username"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                placeholder="••••••••" required autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label class="auth-remember">
            <input id="remember_me" type="checkbox" name="remember">
            {{ __('Remember me') }}
        </label>

        <x-primary-button>{{ __('Sign In') }}</x-primary-button>

        @if(Route::has('password.request'))
            <div class="auth-footer-link">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        @endif

    </form>

</x-guest-layout>
