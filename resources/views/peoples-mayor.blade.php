<x-app-layout>
@php
    $title = "The People's Mayor";
    $page = app(\App\Settings\PeoplesMayorPageSettings::class);

    $projects = \App\Models\Project::published()->with('media')->orderBy('sort_order')->get();
    $communityPhotos = \App\Models\CommunityPhoto::published()
        ->group(\App\Models\CommunityPhoto::GROUP_MUNICIPALITY)
        ->with('media')->orderBy('sort_order')->take(6)->get();

    // Placeholder icons cycled across project cards that have no photo yet.
    $projectIcons = [
        '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        '<path d="M12 22V8"/><path d="M12 8a4 4 0 0 0-4-4 4 4 0 0 0 4 8 4 4 0 0 0 4-8 4 4 0 0 0-4 4z"/>',
        '<path d="M4 19l4-14"/><path d="M20 19L16 5"/><path d="M12 5v3"/><path d="M12 12v3"/><path d="M12 19v0"/>',
        '<path d="M3 9l1-5h16l1 5"/><path d="M4 9v11a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9"/><path d="M9 21v-6h6v6"/>',
        '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ];

    $communityIcon = '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
@endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">The People's Mayor</span>
                </nav>
                <span class="page-hero__eyebrow">{{ $page->hero_eyebrow }}</span>
                <h1 class="page-hero__title">{{ $page->hero_title }}</h1>
                <p class="page-hero__subtitle">{!! nl2br(e($page->hero_subtitle)) !!}</p>
            </div>
        </div>
    </section>

    {{-- ── Impact Stats ─────────────────────────────────────────────────────── --}}
    @if ($page->impact_stats)
    <section class="impact-stats">
        <div class="container">
            <div class="impact-stats__grid">
                @foreach ($page->impact_stats as $i => $stat)
                    @php $count = (int) preg_replace('/[^0-9]/', '', $stat['value'] ?? ''); @endphp
                    <div class="impact-stats__item" data-reveal data-reveal-delay="{{ $i * 80 }}">
                        <div class="impact-stats__number">
                            <span @if ($count) data-count="{{ $count }}" @endif>{{ $stat['value'] ?? '' }}</span>
                            <span class="impact-stats__suffix">{{ $stat['suffix'] ?? '' }}</span>
                        </div>
                        <div class="impact-stats__label">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── Project Showcase ─────────────────────────────────────────────────── --}}
    @if ($projects->isNotEmpty())
    <section class="project-showcase">
        @foreach ($projects as $i => $project)
        <div class="project-item" data-reveal data-reveal-delay="{{ $i % 2 === 0 ? 0 : 100 }}">

            <div class="project-item__image">
                @if ($project->imageUrl())
                    <img src="{{ $project->imageUrl() }}" alt="{{ $project->title }}" loading="lazy">
                @else
                    <div class="project-item__image-placeholder">
                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            {!! $projectIcons[$i % count($projectIcons)] !!}
                        </svg>
                        @if ($project->tag)
                            <span class="project-item__image-tag">{{ $project->tag }}</span>
                        @endif
                    </div>
                @endif
                <div class="project-item__image-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            </div>

            <div class="project-item__body">
                <div class="project-item__number">Project {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                <h2 class="project-item__title">{{ $project->title }}</h2>
                <p class="project-item__desc">{{ $project->description }}</p>
                @if ($project->metrics)
                <div class="project-item__metrics">
                    @foreach ($project->metrics as $metric)
                    <div class="project-item__metric">
                        <span class="project-item__metric-value">{{ $metric['value'] ?? '' }}</span>
                        <span class="project-item__metric-label">{{ $metric['label'] ?? '' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
        @endforeach
    </section>
    @else
    <section class="section">
        <div class="container">
            <x-empty-state message="Detailed project write-ups will appear here soon." />
        </div>
    </section>
    @endif

    {{-- ── Pull Quote ───────────────────────────────────────────────────────── --}}
    @if ($page->pull_quote)
    <div class="mayor-quote">
        <div class="container">
            <p class="mayor-quote__text" data-reveal>{{ $page->pull_quote }}</p>
            @if ($page->pull_quote_attribution)
                <div class="mayor-quote__author" data-reveal data-reveal-delay="100">{{ $page->pull_quote_attribution }}</div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Community Photo Grid ─────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $page->community_eyebrow }}</span>
                <h2 class="section-header__title">{{ $page->community_headline }}</h2>
                <p class="section-header__lead">{{ $page->community_lead }}</p>
            </div>

            @if ($communityPhotos->isNotEmpty())
            <div class="community-grid">
                @foreach ($communityPhotos as $i => $photo)
                <div class="community-photo" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    @if ($photo->imageUrl())
                        <img src="{{ $photo->imageUrl() }}" alt="{{ $photo->caption }}" loading="lazy">
                    @else
                        <div class="community-photo__placeholder">
                            <svg class="community-photo__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $communityIcon !!}</svg>
                        </div>
                    @endif
                    <div class="community-photo__overlay">
                        @if ($photo->tag)
                            <span class="community-photo__tag">{{ $photo->tag }}</span>
                        @endif
                        <p class="community-photo__caption">{{ $photo->caption }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state message="Photos from around the municipality are coming soon." />
            @endif

        </div>
    </section>

    {{-- ── Note on figures ──────────────────────────────────────────────────── --}}
    @if ($page->figures_note)
    <section class="section section--tight">
        <div class="container">
            <p style="max-width:760px; margin:0 auto; font-size:0.85rem; color:var(--muted); text-align:center;">{{ $page->figures_note }}</p>
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
