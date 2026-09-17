<x-app-layout title="About" styles="about" description="The story of Talib Ahmed Bensouda — from Kanifing Municipal Council to a national vision for The Gambia.">
@php
    $page = app(\App\Settings\AboutPageSettings::class);

    $valueIcons = [
        '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
        '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ];

    // One icon per milestone `category` (see AboutPageSettings::TIMELINE_CATEGORIES).
    $milestoneIcons = [
        'personal' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'education' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'election' => '<circle cx="12" cy="12" r="10"/><polyline points="8 12 11 15 16 9"/>',
        'project' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        'environment' => '<path d="M11 20A7 7 0 0 1 4 13c0-4 4-9 12-9 0 8-3 12-7 12z"/><path d="M4 20c3-3 6-6 12-15"/>',
        'national' => '<path d="M3 11v3a1 1 0 0 0 1 1h3l4 4V6l-4 4H4a1 1 0 0 0-1 1z"/><path d="M16 8a4 4 0 0 1 0 8"/><path d="M19 5a8 8 0 0 1 0 14"/>',
    ];

    // Two columns, each read top-to-bottom: left finishes where right begins.
    $milestones = collect($page->timeline ?? [])->values();
    $milestoneHalf = (int) ceil($milestones->count() / 2);
    $milestonesLeft = $milestones->slice(0, $milestoneHalf)->values();
    $milestonesRight = $milestones->slice($milestoneHalf)->values();
@endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">About</span>
                </nav>
                <span class="page-hero__eyebrow">{{ $page->hero_eyebrow }}</span>
                <h1 class="page-hero__title">{{ $page->hero_title }}</h1>
                <p class="page-hero__subtitle">{!! nl2br(e($page->hero_subtitle)) !!}</p>
            </div>
        </div>
    </section>

    {{-- ── Bio ────────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="about-bio">

                <div class="about-bio__photo-wrap" data-reveal="fade-right">
                    <div class="about-bio__photo">
                        <img src="{{ $page->bioPhotoUrl() }}" alt="{{ $page->hero_title }}" loading="lazy">
                    </div>
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <span class="about-bio__eyebrow">{{ $page->bio_eyebrow }}</span>
                    <h2 class="about-bio__title">{{ $page->bio_headline }}</h2>
                    @foreach ($page->paragraphs('bio_body') as $paragraph)
                        <p class="about-bio__text">{{ $paragraph }}</p>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- ── Timeline ─────────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->timeline_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->timeline_headline }}</h2>
                <p class="section-header__lead">{{ $page->timeline_lead }}</p>
            </div>

            @if ($milestones->isNotEmpty())
            <div class="milestone-grid">
                <div class="milestone-grid__col">
                    @foreach ($milestonesLeft as $i => $item)
                    <div class="milestone-row" data-reveal data-reveal-delay="{{ $i * 60 }}">
                        <div class="milestone-row__year">{{ $item['year'] ?? '' }}</div>
                        <div class="milestone-row__marker">
                            <div class="milestone-row__dot">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $milestoneIcons[$item['category'] ?? ''] ?? $milestoneIcons['project'] !!}
                                </svg>
                            </div>
                        </div>
                        <div class="milestone-row__content">
                            <h3 class="milestone-row__title">{{ $item['title'] ?? '' }}</h3>
                            <p class="milestone-row__desc">{{ $item['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="milestone-grid__col">
                    @foreach ($milestonesRight as $i => $item)
                    <div class="milestone-row" data-reveal data-reveal-delay="{{ $i * 60 }}">
                        <div class="milestone-row__year">{{ $item['year'] ?? '' }}</div>
                        <div class="milestone-row__marker">
                            <div class="milestone-row__dot">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $milestoneIcons[$item['category'] ?? ''] ?? $milestoneIcons['project'] !!}
                                </svg>
                            </div>
                        </div>
                        <div class="milestone-row__content">
                            <h3 class="milestone-row__title">{{ $item['title'] ?? '' }}</h3>
                            <p class="milestone-row__desc">{{ $item['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <x-empty-state message="Career milestones will be added here soon." />
            @endif

        </div>
    </section>

    {{-- ── Values ───────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->values_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->values_headline }}</h2>
                <p class="section-header__lead">{{ $page->values_lead }}</p>
            </div>

            @if ($page->values)
            <div class="values-grid">
                @foreach ($page->values as $i => $value)
                <div class="value-card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            {!! $valueIcons[$i % count($valueIcons)] !!}
                        </svg>
                    </div>
                    <h3 class="value-card__title">{{ $value['title'] ?? '' }}</h3>
                    <p class="value-card__desc">{{ $value['description'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state message="Guiding principles will be added here soon." />
            @endif
        </div>
    </section>

    {{-- ── National role ────────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">
            <div class="party-section">

                <div data-reveal="fade-right">
                    <span class="party-section__eyebrow">{{ $page->national_eyebrow }}</span>
                    <h2 class="party-section__title">{!! nl2br(e($page->national_headline)) !!}</h2>
                    @foreach ($page->paragraphs('national_body') as $paragraph)
                        <p class="party-section__desc">{{ $paragraph }}</p>
                    @endforeach
                    @if ($page->national_pillars)
                    <div class="party-section__pillars">
                        @foreach ($page->national_pillars as $pillar)
                            <div class="party-section__pillar">{{ $pillar }}</div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <div class="party-section__logo-wrap">
                        @if ($page->nationalLogoUrl())
                            <img class="party-section__logo-img" src="{{ $page->nationalLogoUrl() }}" alt="{{ $page->national_logo_name }}" loading="lazy">
                        @else
                            <div class="party-section__logo-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="2" y1="12" x2="22" y2="12"/>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="party-section__logo-name">{{ $page->national_logo_name }}</div>
                        <div class="party-section__logo-caption">{{ $page->national_logo_caption }}</div>
                        @if ($page->national_cta_label)
                            <a href="{{ url($page->national_cta_url) }}" class="btn btn--gold btn--sm" style="margin-top:8px">{{ $page->national_cta_label }}</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>{{ $page->cta_headline }}</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">{{ $page->cta_lead }}</p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                @if ($page->cta_primary_label)
                    <a href="{{ url($page->cta_primary_url) }}" class="btn btn--navy btn--lg">{{ $page->cta_primary_label }}</a>
                @endif
                @if ($page->cta_secondary_label)
                    <a href="{{ url($page->cta_secondary_url) }}" class="btn btn--outline-navy btn--lg">{{ $page->cta_secondary_label }}</a>
                @endif
            </div>
        </div>
    </section>

</x-app-layout>
