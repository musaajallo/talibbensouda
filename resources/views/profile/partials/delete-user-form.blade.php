<section>
    <header>
        <h2>{{ __('Delete Account') }}</h2>
        <p>{{ __('Once deleted, all data is permanently removed. Enter your password to confirm.') }}</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Delete account permanently?')">
        @csrf
        @method('delete')

        <div class="form-group">
            <x-input-label for="delete_password" :value="__('Password')" />
            <x-text-input id="delete_password" name="password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->userDeletion->get('password')" />
        </div>

        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
    </form>
</section>
