<x-app-layout title="Sitemap">
@php
    $events = \App\Models\Event::orderBy('sort_order')->get();
    $chrome = app(\App\Settings\SiteChromeSettings::class);
@endphp

    {{-- ── Mini header (matches Image #12 style) ──────────────────────────── --}}
    <section class="sitemap-header">
        <div class="container">
            <nav class="page-hero__breadcrumb" aria-label="Breadcrumb" style="margin-bottom:20px;">
                <a href="{{ url('/') }}">Home</a>
                <span>/</span>
                <span style="opacity:0.55">Sitemap</span>
            </nav>
            <div class="sitemap-header__inner">
                <span class="sitemap-header__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                </span>
                <div>
                    <h1 class="sitemap-header__title">Sitemap</h1>
                    <p class="sitemap-header__subtitle">Complete overview of all pages on this website.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Card grid ────────────────────────────────────────────────────────── --}}
    <div class="container">
        <div class="sitemap-grid">

            {{-- Main Pages --}}
            <div class="sitemap-card" data-reveal>
                <div class="sitemap-card__header">
                    <span class="sitemap-card__icon sitemap-card__icon--navy">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </span>
                    <h2 class="sitemap-card__title">Main Pages</h2>
                </div>
                <ul class="sitemap-card__list">
                    <li><a href="{{ url('/') }}"            class="sitemap-card__link">Home</a></li>
                    <li><a href="{{ url('/about') }}"       class="sitemap-card__link">About</a></li>
                    <li><a href="{{ url('/gallery') }}"     class="sitemap-card__link">Gallery</a></li>
                    <li><a href="{{ url('/giving-back') }}" class="sitemap-card__link">Giving Back</a></li>
                    <li><a href="{{ url('/contact') }}"     class="sitemap-card__link">Get in Touch</a></li>
                </ul>
            </div>

            {{-- The People's Mayor --}}
            <div class="sitemap-card" data-reveal data-reveal-delay="60">
                <div class="sitemap-card__header">
                    <span class="sitemap-card__icon sitemap-card__icon--gold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </span>
                    <h2 class="sitemap-card__title">The People's Mayor</h2>
                </div>
                <ul class="sitemap-card__list">
                    <li><a href="{{ url('/peoples-mayor') }}" class="sitemap-card__link">Overview</a></li>
                </ul>
            </div>

            {{-- Events --}}
            <div class="sitemap-card" data-reveal data-reveal-delay="120">
                <div class="sitemap-card__header">
                    <span class="sitemap-card__icon sitemap-card__icon--green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <h2 class="sitemap-card__title">Events</h2>
                </div>
                <ul class="sitemap-card__list">
                    <li><a href="{{ url('/events') }}" class="sitemap-card__link">All Events</a></li>
                    <li><a href="{{ url('/events/register') }}" class="sitemap-card__link">Register Your Interest</a></li>
                    @foreach($events as $event)
                        <li><a href="{{ route('events.show', $event) }}" class="sitemap-card__link">{{ $event->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Campaign --}}
            <div class="sitemap-card" data-reveal data-reveal-delay="60">
                <div class="sitemap-card__header">
                    <span class="sitemap-card__icon sitemap-card__icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <h2 class="sitemap-card__title">Campaign</h2>
                </div>
                <ul class="sitemap-card__list">
                    @if ($chrome->join_party_url)
                        <li><a href="{{ $chrome->join_party_url }}" target="_blank" rel="noopener" class="sitemap-card__link">{{ $chrome->join_party_label }}</a></li>
                    @endif
                    <li><a href="{{ url('/giving-back') }}" class="sitemap-card__link">Giving Back</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="sitemap-card" data-reveal data-reveal-delay="180">
                <div class="sitemap-card__header">
                    <span class="sitemap-card__icon sitemap-card__icon--rose">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    <h2 class="sitemap-card__title">Legal</h2>
                </div>
                <ul class="sitemap-card__list">
                    <li><a href="{{ url('/privacy') }}" class="sitemap-card__link">Privacy Policy</a></li>
                    <li><a href="{{ url('/cookies') }}" class="sitemap-card__link">Cookie Policy</a></li>
                    <li><a href="{{ url('/sitemap') }}" class="sitemap-card__link">Sitemap</a></li>
                </ul>
            </div>

        </div>
    </div>

</x-app-layout>
