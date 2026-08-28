<x-app-layout>

    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <section class="hero"
        x-data="{
            slides: [
                { img: '/images/hero-talib-desk.webp', bg: '#0d1b38', pos: 'center' },
                { img: '/images/hero-masquerade.webp', bg: '#0a1525', pos: 'center' },
                { img: '/images/hero-supporters.webp', bg: '#112044', pos: 'center right' },
                { img: '/images/hero-hall.webp',       bg: '#091422', pos: 'center' },
                { img: '/images/hero-victory.webp',    bg: '#0d1b38', pos: 'center right' },
            ],
            current: 0,
            timer: null,
            start() { this.timer = setInterval(() => this.next(), 5000) },
            next() { this.current = (this.current + 1) % this.slides.length },
            goTo(i) { this.current = i; clearInterval(this.timer); this.start() }
        }"
        x-init="start()"
    >
        <div class="hero__slides">
            <template x-for="(slide, i) in slides" :key="i">
                <div class="hero__slide" :class="{ 'is-active': i === current }"
                     :style="slide.img
                         ? `background-color: ${slide.bg}; background-image: url('${slide.img}'); background-position: ${slide.pos || 'center'}`
                         : `background-color: ${slide.bg}`"></div>
            </template>
        </div>

        <div class="hero__inner container">
            <div class="hero__content">
                <span class="hero__eyebrow">Lord Mayor of Kanifing</span>
                <h1 class="hero__title">
                    A record of <em>delivery</em><br>for The Gambia
                </h1>
                <p class="hero__lead">
                    Since 2018, Kanifing Municipal Council under Talib Bensouda has built
                    a municipality-wide waste system, dozens of kilometres of new roads,
                    markets, clinics and a public library. This is the record.
                </p>
                <div class="hero__actions">
                    <a href="{{ url('/peoples-mayor') }}" class="btn btn--gold btn--lg">See the Record</a>
                    <a href="{{ url('/about') }}" class="btn btn--outline-white btn--lg">About Talib</a>
                </div>
            </div>
        </div>

        <div class="hero__dots">
            <template x-for="(slide, i) in slides" :key="i">
                <button class="hero__dot" :class="{ 'is-active': i === current }" @click="goTo(i)" :aria-label="`Go to slide ${i + 1}`"></button>
            </template>
        </div>
    </section>

    {{-- ── Stats Bar ───────────────────────────────────────────────────────── --}}
    <section class="stats-bar">
        <div class="container">
            <div class="stats-bar__grid">
                @foreach([
                    ['24',  '',   'Compactor Trucks Procured',   24],
                    ['38',  'km', 'New Municipal Roads',         38],
                    ['9',   '',   'Ambulances to Clinics',        9],
                    ['200', '%',  'Revenue Growth, 2017–22',    200],
                ] as [$num, $suffix, $label, $count])
                <div class="stats-bar__item">
                    <div class="stats-bar__number">
                        <span data-count="{{ $count }}">{{ $num }}</span>
                        <span class="stats-bar__suffix">{{ $suffix }}</span>
                    </div>
                    <div class="stats-bar__label">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── About Teaser ─────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="about-teaser">

                <div class="about-teaser__image" data-reveal="fade-right">
                    <div class="about-teaser__img-placeholder">Photo</div>
                </div>

                <div class="about-teaser__body" data-reveal="fade-left" data-reveal-delay="150">
                    <span class="about-teaser__eyebrow">About Talib</span>
                    <h2 class="about-teaser__title">Elected at 31.<br>The youngest mayor<br>in Gambian history.</h2>
                    <p class="about-teaser__text">
                        Talib Ahmed Bensouda was born in Bakau in 1986 and studied Economics
                        and Communication Technology at the University of Toronto. He worked
                        in business before entering local government, and in May 2018 was
                        elected Mayor of Kanifing Municipal Council, defeating the APRC
                        candidate with 29,325 votes. He was re-elected in 2023.
                    </p>
                    <a href="{{ url('/about') }}" class="btn btn--outline-navy">Read Full Bio</a>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Featured Video ─────────────────────────────────────────────────── --}}
    <section class="video-section">
        <div class="container">
            <div class="video-section__inner">

                <div data-reveal="fade-right">
                    <span class="video-section__eyebrow">In Focus</span>
                    <h2 class="video-section__title">"Together for a Better KM"</h2>
                    <p class="video-section__desc">
                        Both of Talib Bensouda's mayoral campaigns were run on a
                        development-first manifesto and were noted for avoiding partisan
                        attacks. The slogan became the framework for two terms of work
                        across Kanifing's 19 wards.
                    </p>
                    <blockquote class="video-section__quote">
                        Kanifing's first structured, municipality-wide waste collection
                        system — and the first of its kind in the sub-region.
                    </blockquote>
                </div>

                <div class="video-section__player" data-reveal="fade-left" data-reveal-delay="150">
                    {{--
                        To add the real video, replace the placeholder div below with:
                        <iframe src="https://www.youtube.com/embed/YOUR_VIDEO_ID"
                                allowfullscreen allow="autoplay; encrypted-media"></iframe>
                    --}}
                    <div class="video-section__placeholder">
                        <button class="video-section__play-btn" aria-label="Play video">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="#0d1b38"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </button>
                        <span class="video-section__placeholder-label">Video coming soon</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── The People's Mayor ───────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">The Record</span>
                <h2 class="section-header__title">The People's Mayor</h2>
                <p class="section-header__lead">
                    Six areas where Kanifing Municipal Council's work under Talib Bensouda
                    is most visible.
                </p>
            </div>

            <div class="projects-grid">
                @foreach([
                    ['The Mbalit Project',            'Kanifing\'s first municipality-wide waste collection system — a fully funded D130 million partnership with 24 new compactor trucks and 172 youth jobs.'],
                    ['Environmental Transformation',  'A €3 million EU-funded programme covering waste, education and tree planting — including the plan to plant 190,000 trees across 19 wards.'],
                    ['Road Network Project',          'More than D300 million of Council funding for 38 kilometres of new roads, 11 feeder roads and two bridges connecting all 19 wards.'],
                    ['Markets Rebuilt',               'Latrikunda Sabiji rebuilt with 100 shops; Serrekunda Market upgraded with a women\'s shed, CCTV, boreholes and security lighting.'],
                    ['Healthcare Access',             'Nine ambulances for nine community clinics, a D15 million expansion of the Bundung Maternity Ward, and support for local clinics.'],
                    ['Jobs, Skills & Enterprise',     'A D20 million youth revolving fund, the "Tekki Fii" innovation challenge, skills centres and 155 sewing machines for community centres.'],
                ] as $i => $project)
                <div class="project-card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="project-card__image">Photo {{ $i + 1 }}</div>
                    <div class="project-card__body">
                        <div class="project-card__number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <h3 class="project-card__title">{{ $project[0] }}</h3>
                        <p class="project-card__desc">{{ $project[1] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align:center; margin-top:48px;" data-reveal data-reveal-delay="400">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--outline-navy">See All Projects</a>
            </div>

        </div>
    </section>

    {{-- ── In the Community ──────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">On the Ground</span>
                <h2 class="section-header__title">Across the Municipality</h2>
                <p class="section-header__lead">
                    Openings, launches and community work across Kanifing's 19 wards —
                    from Bakau and Bakoteh to Latrikunda, Tallinding and Serrekunda.
                </p>
            </div>

            @php
            $communityPhotos = [
                ['Library',     'Municipal Library & Innovation Hub — opened 2024'],
                ['Waste',       'Mbalit household collection rollout'],
                ['Youth',       'Bakoteh Production & Innovation Centre'],
                ['Markets',     'Serrekunda Market upgrade'],
                ['Environment', 'Bakoteh dumpsite fencing & remediation'],
                ['Community',   'Ward development across 19 wards'],
            ];
            @endphp

            <div class="community-grid">
                @foreach($communityPhotos as $i => [$tag, $caption])
                <div class="community-photo" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="community-photo__placeholder">
                        <svg class="community-photo__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <div class="community-photo__overlay">
                        <span class="community-photo__tag">{{ $tag }}</span>
                        <p class="community-photo__caption">{{ $caption }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── Recognition ─────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Recognition</span>
                <h2 class="section-header__title">Noted Beyond The Gambia</h2>
                <p class="section-header__lead">
                    Kanifing's work has drawn recognition and partnerships from cities
                    and institutions around the world.
                </p>
            </div>

            <div class="testimonials-grid">

                <div class="testimonial-card testimonial-card--featured" data-reveal data-reveal-delay="0">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        New Castle County, Delaware declared 8 July "Kanifing Municipal
                        Council Day," and later marked 19 May 2022 as "Lord Mayor Bensouda
                        Day" — recognition announced during a visit by the County Executive.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">New Castle County, Delaware</div>
                            <div class="testimonial-card__role">United States · 2022</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card" data-reveal data-reveal-delay="100">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        KMC holds full membership of the Global Parliament of Mayors and
                        United Cities and Local Governments of Africa, and sits on the
                        Mayors Migration Council via the Africa–Europe Mayors' Dialogue.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">Global Parliament of Mayors</div>
                            <div class="testimonial-card__role">International membership</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card" data-reveal data-reveal-delay="200">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        A Dubawa fact-check in December 2025 independently verified several
                        of Kanifing Municipal Council's market, road and library projects
                        against public records.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">Dubawa Fact-Check</div>
                            <div class="testimonial-card__role">December 2025</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Recent Milestones ────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Recently</span>
                <h2 class="section-header__title section-header__title--light">Recent Milestones</h2>
                <p class="section-header__lead section-header__lead--light">
                    The latest openings and launches across the municipality.
                </p>
            </div>

            <div class="events-list">
                @foreach([
                    ['01', 'Jun', '2026', 'Digital Addresses for Policymaking', 'Research partnership with Paris Dauphine, Sciences Po and UTG, building on 31,867 digital property addresses'],
                    ['01', 'Jul', '2025', 'UN Deputy Secretary-General Visit',  'Amina J. Mohammed visits the Bakoteh Youth Skills Acquisition Centre'],
                    ['01', 'Jun', '2025', 'Bakau Multipurpose Facility',        'A D10 million-plus community facility launched, serving around 520 beneficiaries, most of them women'],
                    ['01', 'Dec', '2024', 'Municipal Library & Innovation Hub',  'The D45 million KETP-funded public library, career centre and innovation hub inaugurated'],
                ] as $i => [$day, $month, $year, $title, $desc])
                <div class="event-row" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="event-row__date">
                        <div class="event-row__date-day">{{ $day }}</div>
                        <div class="event-row__date-month">{{ $month }} {{ $year }}</div>
                    </div>
                    <div class="event-row__info">
                        <div class="event-row__title">{{ $title }}</div>
                        <div class="event-row__meta">{{ $desc }}</div>
                    </div>
                    <div class="event-row__cta">
                        <a href="{{ url('/events') }}" class="btn btn--gold btn--sm">Details</a>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align:center; margin-top:48px;" data-reveal data-reveal-delay="350">
                <a href="{{ url('/events') }}" class="btn btn--outline-white">Events &amp; Milestones</a>
            </div>

        </div>
    </section>

    {{-- ── CTA Banner ───────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Follow the Work</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                Explore the full record from two terms in Kanifing, or get in touch
                with the team directly.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--navy btn--lg">See the Record</a>
                <a href="{{ url('/contact') }}" class="btn btn--outline-navy btn--lg">Get in Touch</a>
            </div>
        </div>
    </section>

</x-app-layout>
