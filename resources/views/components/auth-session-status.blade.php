@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'form-alert form-alert--success']) }} style="display:flex; align-items:flex-start; gap:10px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ $status }}
    </div>
@endif
