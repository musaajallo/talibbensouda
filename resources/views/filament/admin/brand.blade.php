@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $appName = config('app.name');
    // An admin upload (site_logo on GeneralSettings) wins in both themes.
    // Otherwise fall back to a Montserrat wordmark.
    $uploaded = $settings->site_logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings->site_logo)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings->site_logo)
        : null;
@endphp

@if ($uploaded)
    <img src="{{ $uploaded }}" alt="{{ $appName }}" class="h-9 w-auto shrink-0 object-contain" />
@else
    <span class="flex items-baseline gap-1.5"
          style="font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;">
        <span class="text-lg font-extrabold tracking-tight text-[#0d1b38] dark:text-white">Talib Bensouda</span>
        <span class="text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-[#c9a227]">Admin</span>
    </span>
@endif
