<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email"
                :value="old('email', $request->email)"
                placeholder="your@email.com" required autofocus autocomplete="username"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" type="password" name="password"
                placeholder="At least 8 characters" required autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                placeholder="Repeat your new password" required autocomplete="new-password"
                class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>{{ __('Set New Password') }}</x-primary-button>

    </form>

</x-guest-layout>
