<x-app-layout>
@php
    $home = app(\App\Settings\HomePageSettings::class);
    $slides = $home->heroSlides();

    $projects = \App\Models\Project::published()->with('media')->orderBy('sort_order')->take(6)->get();
    $communityPhotos = \App\Models\CommunityPhoto::published()
        ->group(\App\Models\CommunityPhoto::GROUP_MUNICIPALITY)
        ->with('media')->orderBy('sort_order')->take(6)->get();
    $testimonials = \App\Models\Testimonial::published()->orderBy('sort_order')->get();
    $milestones = \App\Models\Event::where('is_upcoming', true)->orderBy('sort_order')->take(4)->get();

    // Decorative avatar shapes, cycled across recognition cards.
    $avatarIcons = [
        '<path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>',
        '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/>',
        '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ];
@endphp

    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <section class="hero"
        x-data="{
            slides: {{ Js::from($slides) }},
            current: 0,
            timer: null,
            start() { if (this.slides.length > 1) this.timer = setInterval(() => this.next(), 5000) },
            next() { this.current = (this.current + 1) % this.slides.length },
            goTo(i) { this.current = i; clearInterval(this.timer); this.start() }
        }"
        x-init="start()"
    >
        <div class="hero__slides">
            <template x-for="(slide, i) in slides" :key="i">
                <div class="hero__slide" :class="{ 'is-active': i === current }"
                     :style="slide.img
                         ? `background-color: ${slide.bg}; background-image: url('${slide.img}'); background-position: ${slide.pos || 'center top'}`
                         : `background-color: ${slide.bg}`"></div>
            </template>
        </div>

        <div class="hero__inner container">
            <div class="hero__content">
                <span class="hero__eyebrow">{{ $home->hero_eyebrow }}</span>
                <h1 class="hero__title">{!! $home->headlineHtml() !!}</h1>
                <p class="hero__lead">{{ $home->hero_lead }}</p>
                <div class="hero__actions">
                    @if ($home->hero_primary_label)
                        <a href="{{ url($home->hero_primary_url) }}" class="btn btn--gold btn--lg">{{ $home->hero_primary_label }}</a>
                    @endif
                    @if ($home->hero_secondary_label)
                        <a href="{{ url($home->hero_secondary_url) }}" class="btn btn--outline-white btn--lg">{{ $home->hero_secondary_label }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="hero__dots" x-show="slides.length > 1">
            <template x-for="(slide, i) in slides" :key="i">
                <button class="hero__dot" :class="{ 'is-active': i === current }" @click="goTo(i)" :aria-label="`Go to slide ${i + 1}`"></button>
            </template>
        </div>
    </section>

    {{-- ── Stats Bar ───────────────────────────────────────────────────────── --}}
    @if ($home->stats)
    <section class="stats-bar">
        <div class="container">
            <div class="stats-bar__grid">
                @foreach ($home->stats as $stat)
                    @php $count = (int) preg_replace('/[^0-9]/', '', $stat['value'] ?? ''); @endphp
                    <div class="stats-bar__item">
                        <div class="stats-bar__number">
                            <span @if ($count) data-count="{{ $count }}" @endif>{{ $stat['value'] ?? '' }}</span>
                            <span class="stats-bar__suffix">{{ $stat['suffix'] ?? '' }}</span>
                        </div>
                        <div class="stats-bar__label">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── About Teaser ─────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="about-teaser">

                <div class="about-teaser__image" data-reveal="fade-right">
                    <img src="{{ $home->aboutImageUrl() }}" alt="Talib Ahmed Bensouda" loading="lazy">
                </div>

                <div class="about-teaser__body" data-reveal="fade-left" data-reveal-delay="150">
                    <span class="about-teaser__eyebrow">{{ $home->about_eyebrow }}</span>
                    <h2 class="about-teaser__title">{!! nl2br(e($home->about_headline)) !!}</h2>
                    <p class="about-teaser__text">{{ $home->about_body }}</p>
                    @if ($home->about_cta_label)
                        <a href="{{ url('/about') }}" class="btn btn--outline-navy">{{ $home->about_cta_label }}</a>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ── Featured Video ─────────────────────────────────────────────────── --}}
    <section class="video-section">
        <div class="container">
            <div class="video-section__inner">

                <div data-reveal="fade-right">
                    <span class="video-section__eyebrow">{{ $home->video_eyebrow }}</span>
                    <h2 class="video-section__title">{{ $home->video_headline }}</h2>
                    <p class="video-section__desc">{{ $home->video_body }}</p>
                    @if ($home->video_quote)
                        <blockquote class="video-section__quote">{{ $home->video_quote }}</blockquote>
                    @endif
                </div>

                <div class="video-section__player" data-reveal="fade-left" data-reveal-delay="150">
                    @if ($videoUrl = $home->videoUrl())
                        <video
                            src="{{ $videoUrl }}"
                            controls
                            preload="metadata"
                            playsinline
                            poster="{{ asset('images/hero-victory.webp') }}"></video>
                    @else
                        <iframe
                            src="https://www.youtube-nocookie.com/embed/{{ $home->video_youtube_id }}"
                            title="{{ $home->video_headline }}"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ── The People's Mayor ───────────────────────────────────────────────── --}}
    @if ($projects->isNotEmpty())
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $home->projects_eyebrow }}</span>
                <h2 class="section-header__title">{{ $home->projects_headline }}</h2>
                <p class="section-header__lead">{{ $home->projects_lead }}</p>
            </div>

            <div class="projects-grid">
                @foreach ($projects as $i => $project)
                <div class="project-card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="project-card__image">
                        @if ($project->imageUrl())
                            <img src="{{ $project->imageUrl() }}" alt="{{ $project->title }}" loading="lazy">
                        @else
                            Photo {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="project-card__body">
                        <div class="project-card__number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <h3 class="project-card__title">{{ $project->title }}</h3>
                        <p class="project-card__desc">{{ $project->cardText() }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            @if ($home->projects_cta_label)
            <div style="text-align:center; margin-top:48px;" data-reveal data-reveal-delay="400">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--outline-navy">{{ $home->projects_cta_label }}</a>
            </div>
            @endif

        </div>
    </section>
    @endif

    {{-- ── In the Community ──────────────────────────────────────────────────── --}}
    @if ($communityPhotos->isNotEmpty())
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $home->community_eyebrow }}</span>
                <h2 class="section-header__title">{{ $home->community_headline }}</h2>
                <p class="section-header__lead">{{ $home->community_lead }}</p>
            </div>

            <div class="community-grid">
                @foreach ($communityPhotos as $i => $photo)
                <div class="community-photo" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    @if ($photo->imageUrl())
                        <img src="{{ $photo->imageUrl() }}" alt="{{ $photo->caption }}" loading="lazy">
                    @else
                        <div class="community-photo__placeholder">
                            <svg class="community-photo__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
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

        </div>
    </section>
    @endif

    {{-- ── Recognition ─────────────────────────────────────────────────────── --}}
    @if ($testimonials->isNotEmpty())
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $home->recognition_eyebrow }}</span>
                <h2 class="section-header__title">{{ $home->recognition_headline }}</h2>
                <p class="section-header__lead">{{ $home->recognition_lead }}</p>
            </div>

            <div class="testimonials-grid">
                @foreach ($testimonials as $i => $testimonial)
                <div class="testimonial-card {{ $testimonial->featured ? 'testimonial-card--featured' : '' }}" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">{{ $testimonial->quote }}</p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">{!! $avatarIcons[$i % count($avatarIcons)] !!}</svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">{{ $testimonial->name }}</div>
                            @if ($testimonial->role)
                                <div class="testimonial-card__role">{{ $testimonial->role }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── Recent Milestones ────────────────────────────────────────────────── --}}
    @if ($milestones->isNotEmpty())
    <section class="section section--navy">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">{{ $home->milestones_eyebrow }}</span>
                <h2 class="section-header__title section-header__title--light">{{ $home->milestones_headline }}</h2>
                <p class="section-header__lead section-header__lead--light">{{ $home->milestones_lead }}</p>
            </div>

            <div class="events-list">
                @foreach ($milestones as $i => $event)
                <div class="event-row" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="event-row__date">
                        <div class="event-row__date-day">{{ $event->date_day }}</div>
                        <div class="event-row__date-month">{{ $event->date_month }} {{ $event->date_year }}</div>
                    </div>
                    <div class="event-row__info">
                        <div class="event-row__title">{{ $event->title }}</div>
                        <div class="event-row__meta">{{ $event->description }}</div>
                    </div>
                    <div class="event-row__cta">
                        <a href="{{ route('events.show', $event) }}" class="btn btn--gold btn--sm">Details</a>
                    </div>
                </div>
                @endforeach
            </div>

            @if ($home->milestones_cta_label)
            <div style="text-align:center; margin-top:48px;" data-reveal data-reveal-delay="350">
                <a href="{{ url('/events') }}" class="btn btn--outline-white">{{ $home->milestones_cta_label }}</a>
            </div>
            @endif

        </div>
    </section>
    @endif

    {{-- ── CTA Banner ───────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>{{ $home->cta_headline }}</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">{{ $home->cta_lead }}</p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                @if ($home->cta_primary_label)
                    <a href="{{ url($home->cta_primary_url) }}" class="btn btn--navy btn--lg">{{ $home->cta_primary_label }}</a>
                @endif
                @if ($home->cta_secondary_label)
                    <a href="{{ url($home->cta_secondary_url) }}" class="btn btn--outline-navy btn--lg">{{ $home->cta_secondary_label }}</a>
                @endif
            </div>
        </div>
    </section>

</x-app-layout>
