<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn--gold']) }}>
    {{ $slot }}
</button>
