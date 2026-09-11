@php
    $analyticsDomain = config('services.analytics.domain');
@endphp
@if ($analyticsDomain)
    {{-- Cookieless, no consent gate required. If you switch to a provider that
         sets cookies, gate this behind the banner's analytics toggle instead. --}}
    <script defer data-domain="{{ $analyticsDomain }}" src="{{ config('services.analytics.src') }}"></script>
@endif
