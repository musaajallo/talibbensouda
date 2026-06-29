<x-guest-layout>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                placeholder="••••••••" required autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button>{{ __('Confirm Password') }}</x-primary-button>

    </form>

</x-guest-layout>
