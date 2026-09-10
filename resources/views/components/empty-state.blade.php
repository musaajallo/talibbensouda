@props([
    'message' => 'Content will be published here soon.',
])

<div {{ $attributes->class('empty-state') }}>
    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="4" width="18" height="16" rx="2"/>
        <path d="M3 9h18"/>
        <path d="M9 14h6"/>
    </svg>
    <p class="empty-state__text">{{ $slot->isNotEmpty() ? $slot : $message }}</p>
</div>
