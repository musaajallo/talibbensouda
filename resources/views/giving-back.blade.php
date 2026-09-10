<x-app-layout>
@php
    $title = 'Community Support';
    $page = app(\App\Settings\GivingBackPageSettings::class);

    $programmes = \App\Models\GivingProgramme::published()->orderBy('sort_order')->get();
    $photos = \App\Models\CommunityPhoto::published()
        ->group(\App\Models\CommunityPhoto::GROUP_COMMUNITY_SUPPORT)
        ->with('media')->orderBy('sort_order')->take(6)->get();

    $palette = ['#0d1b38', '#0a1931', '#112044', '#091422', '#0f2040'];

    $programmeIcons = [
        '<path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>',
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/>',
        '<path d="M12 2v20"/><path d="M5 8h14"/><path d="M6 22V10l6-4 6 4v12"/>',
        '<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1 2 3 6 3s6-2 6-3v-5"/>',
        '<circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20"/><path d="M2 12h20"/><circle cx="12" cy="12" r="3"/>',
        '<circle cx="12" cy="8" r="5"/><path d="M12 13v8"/><path d="M9 18h6"/>',
    ];
    $helpIcons = [
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>',
        '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
    ];
    $enterpriseIcons = [
        '<rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h4l3 3v4h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>',
        '<path d="M3 9l1-5h16l1 5"/><path d="M4 9v11a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9"/><path d="M9 21v-6h6v6"/>',
        '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
    ];
    $enterpriseGradients = ['#0d1b38,#112044', '#0a1931,#0d1b38', '#112044,#091422'];
    $photoIcon = '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
@endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Community Support</span>
                </nav>
                <span class="page-hero__eyebrow">{{ $page->hero_eyebrow }}</span>
                <h1 class="page-hero__title">{{ $page->hero_title }}</h1>
                <p class="page-hero__subtitle">{{ $page->hero_subtitle }}</p>
            </div>
        </div>
    </section>

    {{-- ── Intro ────────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="mission-intro">

                <div data-reveal="fade-right">
                    <span class="mission-intro__eyebrow">{{ $page->intro_eyebrow }}</span>
                    <h2 class="mission-intro__title">{!! nl2br(e($page->intro_headline)) !!}</h2>
                    @foreach ($page->paragraphs('intro_body') as $paragraph)
                        <p class="mission-intro__text">{{ $paragraph }}</p>
                    @endforeach
                    @if ($page->intro_quote)
                        <blockquote class="mission-intro__quote">
                            {{ $page->intro_quote }}
                            @if ($page->intro_quote_attribution)
                                <footer style="margin-top:8px; font-size:0.82rem; font-style:normal; color:var(--muted);">{{ $page->intro_quote_attribution }}</footer>
                            @endif
                        </blockquote>
                    @endif
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <div class="mission-intro__stats">
                        @foreach ($page->intro_stats as $i => $stat)
                        <div class="mission-stat" data-reveal data-reveal-delay="{{ $i * 80 }}">
                            <div class="mission-stat__value">{{ $stat['value'] ?? '' }}</div>
                            <div class="mission-stat__label">{{ $stat['label'] ?? '' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Programmes ───────────────────────────────────────────────────────── --}}
    @if ($programmes->isNotEmpty())
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->programmes_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->programmes_headline }}</h2>
                <p class="section-header__lead">{{ $page->programmes_lead }}</p>
            </div>

            <div class="initiatives-grid">
                @foreach ($programmes as $i => $programme)
                <div class="initiative-card" data-reveal data-reveal-delay="{{ ($i % 3) * 80 }}">
                    <div class="initiative-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            {!! $programmeIcons[$i % count($programmeIcons)] !!}
                        </svg>
                    </div>
                    <h3 class="initiative-card__title">{{ $programme->title }}</h3>
                    <p class="initiative-card__desc">{{ $programme->description }}</p>
                    @if ($programme->metric)
                        <span class="initiative-card__metric">{{ $programme->metric }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── Impact Stats ─────────────────────────────────────────────────────── --}}
    @if ($page->impact_stats)
    <section class="giving-stats">
        <div class="container">
            <div class="giving-stats__grid">
                @foreach ($page->impact_stats as $i => $stat)
                    @php $count = (int) preg_replace('/[^0-9]/', '', $stat['value'] ?? ''); @endphp
                    <div class="giving-stats__item" data-reveal data-reveal-delay="{{ $i * 80 }}">
                        <div class="giving-stats__number">
                            <span @if ($count) data-count="{{ $count }}" @endif>{{ $stat['value'] ?? '' }}</span>
                            <span class="giving-stats__suffix">{{ $stat['suffix'] ?? '' }}</span>
                        </div>
                        <div class="giving-stats__label">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── Community Photos ─────────────────────────────────────────────────── --}}
    @if ($photos->isNotEmpty())
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->community_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->community_headline }}</h2>
                <p class="section-header__lead">{{ $page->community_lead }}</p>
            </div>

            <div class="giving-photos">
                @foreach ($photos as $i => $photo)
                @php $bg = $palette[$i % count($palette)]; @endphp
                <div class="giving-photo" style="background-color:{{ $bg }};" data-reveal data-reveal-delay="{{ $i * 70 }}">
                    @if ($photo->imageUrl())
                        <img src="{{ $photo->imageUrl() }}" alt="{{ $photo->caption }}" loading="lazy">
                    @else
                        <div class="giving-photo__placeholder" style="background-color:{{ $bg }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $photoIcon !!}</svg>
                        </div>
                    @endif
                    <div class="giving-photo__overlay">
                        @if ($photo->tag)
                            <div class="giving-photo__tag">{{ $photo->tag }}</div>
                        @endif
                        <p class="giving-photo__caption">{{ $photo->caption }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif

    {{-- ── Municipal enterprises ────────────────────────────────────────────── --}}
    @if ($page->enterprises)
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->enterprises_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->enterprises_headline }}</h2>
                <p class="section-header__lead">{{ $page->enterprises_lead }}</p>
            </div>

            <div class="stories-grid">
                @foreach ($page->enterprises as $i => $enterprise)
                <div class="story-card" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="story-card__image" style="background: linear-gradient(135deg,{{ $enterpriseGradients[$i % count($enterpriseGradients)] }});">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">{!! $enterpriseIcons[$i % count($enterpriseIcons)] !!}</svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">{{ $enterprise['body'] ?? '' }}</p>
                        <div class="story-card__name">{{ $enterprise['title'] ?? '' }}</div>
                        @if (! empty($enterprise['role']))
                            <div class="story-card__role">{{ $enterprise['role'] }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── How to Help ──────────────────────────────────────────────────────── --}}
    @if ($page->help_cards)
    <section class="section section--navy">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->help_eyebrow }}</span>
                <h2 class="section-header__title section-header__title--light">{{ $page->help_headline }}</h2>
                <p class="section-header__lead section-header__lead--light">{{ $page->help_lead }}</p>
            </div>

            <div class="help-grid">
                @foreach ($page->help_cards as $i => $card)
                <div class="help-card" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            {!! $helpIcons[$i % count($helpIcons)] !!}
                        </svg>
                    </div>
                    <h3 class="help-card__title">{{ $card['title'] ?? '' }}</h3>
                    <p class="help-card__desc">{{ $card['description'] ?? '' }}</p>
                    @if (! empty($card['cta_label']))
                        <a href="{{ url($card['cta_url'] ?? '/contact') }}" class="btn btn--gold btn--sm">{{ $card['cta_label'] }}</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
