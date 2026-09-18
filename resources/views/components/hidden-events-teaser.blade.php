@props([
    'count' => 0,
])

@if ($count > 0)
<p {{ $attributes->class('hidden-events-teaser') }}>
    <strong>+{{ $count }}</strong> more {{ Str::plural('event', $count) }} already on the calendar —
    come back soon.
</p>
@endif
