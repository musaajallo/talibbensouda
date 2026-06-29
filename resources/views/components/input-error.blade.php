@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <span {{ $attributes->merge(['class' => 'field-error']) }}>{{ $message }}</span>
    @endforeach
@endif
