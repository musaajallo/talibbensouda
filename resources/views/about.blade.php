<x-app-layout title="About" styles="about">
@php
    $page = app(\App\Settings\AboutPageSettings::class);

    $valueIcons = [
        '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
        '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ];
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

            @if ($page->timeline)
            <div class="timeline">
                @foreach ($page->timeline as $i => $item)
                <div class="tl-item" data-reveal data-reveal-delay="{{ $i * 60 }}">
                    <div class="tl-item__year">{{ $item['year'] ?? '' }}</div>
                    <div class="tl-item__connector">
                        <div class="tl-item__dot"></div>
                        <div class="tl-item__line"></div>
                    </div>
                    <div class="tl-item__content">
                        <h3 class="tl-item__title">{{ $item['title'] ?? '' }}</h3>
                        <p class="tl-item__desc">{{ $item['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
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
