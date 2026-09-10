@php
    $appName = config('app.name');
    $year = now()->year;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="fi-split-login">
        <aside class="fi-split-login-aside">
            <a href="{{ url('/') }}" class="fi-split-login-aside-brand" aria-label="{{ $appName }} — Home">
                {{ $appName }}
            </a>

            <div class="fi-split-login-aside-headline">
                <h1>The People's Mayor.</h1>
                <p>The content and settings behind {{ $appName }}'s website — events, projects, gallery and page copy.</p>
            </div>

            <p class="fi-split-login-aside-footer">© {{ $year }} {{ $appName }}</p>
        </aside>

        <main class="fi-split-login-main">
            <div class="fi-split-login-main-content">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-filament-panels::layout.base>
