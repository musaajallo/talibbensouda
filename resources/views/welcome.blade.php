<x-app-layout>

    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <section class="hero"
        x-data="{
            slides: [
                { bg: '#0d1b38' },
                { bg: '#0a1525' },
                { bg: '#112044' },
                { bg: '#091422' },
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
                <div class="hero__slide" :class="{ 'is-active': i === current }" :style="`background-color: ${slide.bg}`"></div>
            </template>
        </div>

        <div class="hero__inner container">
            <div class="hero__content">
                <span class="hero__eyebrow">Be part of the change makers</span>
                <h1 class="hero__title">
                    Let's <em>UNITE</em> to<br>transform The Gambia
                </h1>
                <p class="hero__lead">
                    Every Gambian has a role to play in bringing change to our beloved nation.
                    Join us to play your part and build the future we deserve together.
                </p>
                <div class="hero__actions">
                    <a href="{{ url('/contact') }}#join" class="btn btn--gold btn--lg">Join Party</a>
                    <a href="{{ url('/contact') }}#volunteer" class="btn btn--outline-white btn--lg">Become a volunteer</a>
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
                    ['50', 'K+',  'Lives Impacted',          50000],
                    ['12', '+',   'Projects Delivered',      12],
                    ['15', '+',   'Years in Public Service', 15],
                    ['100','K+',  'Party Members',           100000],
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
                    <h2 class="about-teaser__title">Mayor. Party Leader.<br>Champion of The Gambia.</h2>
                    <p class="about-teaser__text">
                        Talib Bensouda is a visionary leader and dedicated public servant who has committed
                        his life to the advancement of The Gambia. As Mayor and party leader, he has transformed
                        communities through decisive action, inclusive governance, and an unwavering commitment
                        to the people he serves.
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
                    <span class="video-section__eyebrow">Hear From Talib</span>
                    <h2 class="video-section__title">A Message to Every Gambian</h2>
                    <p class="video-section__desc">
                        In his own words, Talib Bensouda shares his vision for a united,
                        prosperous Gambia — and why every citizen has a role to play
                        in shaping the nation's future.
                    </p>
                    <blockquote class="video-section__quote">
                        "The Gambia does not belong to politicians. It belongs to every man,
                        woman, and child who calls it home."
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
                <span class="section-header__eyebrow">Community Impact</span>
                <h2 class="section-header__title">The People's Mayor</h2>
                <p class="section-header__lead">
                    Six landmark projects that have changed lives and transformed communities
                    across The Gambia.
                </p>
            </div>

            <div class="projects-grid">
                @foreach([
                    ['Clean Water Initiative',       'Bringing piped clean water to underserved communities across the municipality.'],
                    ['Youth Employment Programme',   'Creating sustainable job opportunities for thousands of young Gambians.'],
                    ['Education Infrastructure',     'Building and renovating schools to give every child access to quality education.'],
                    ['Road & Infrastructure',        'Upgrading roads, drainage, and public spaces for safer, connected communities.'],
                    ["Women's Economic Empowerment", 'Microfinance schemes and skills training to unlock economic independence for women.'],
                    ['Healthcare Access',            'Improving and expanding primary healthcare facilities so no one is left behind.'],
                ] as $i => $project)
                <div class="project-card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="project-card__image">Photo {{ $i + 1 }}</div>
                    <div class="project-card__body">
                        <div class="project-card__number">Project {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
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

    {{-- ── Mayor in the Community ─────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Mayor in Action</span>
                <h2 class="section-header__title">With The People</h2>
                <p class="section-header__lead">
                    Talib Bensouda leads from the ground up — standing shoulder-to-shoulder
                    with communities across The Gambia to deliver real, lasting change.
                </p>
            </div>

            @php
            $communityPhotos = [
                ['Community',      'Town Hall Meeting — Banjul'],
                ['Infrastructure', 'Water Project Launch'],
                ['Youth',          'Youth Forum 2026'],
                ['Education',      'School Renovation — Serrekunda'],
                ['Health',         'Community Health Drive'],
                ['Empowerment',    "Women's Workshop — Bakau"],
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

    {{-- ── Testimonials ────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Community Voices</span>
                <h2 class="section-header__title">What People Are Saying</h2>
                <p class="section-header__lead">
                    From Banjul to the diaspora, Gambians across the world are rallying
                    behind Talib Bensouda's vision for their nation.
                </p>
            </div>

            <div class="testimonials-grid">

                <div class="testimonial-card testimonial-card--featured" data-reveal data-reveal-delay="0">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        Talib is not just a mayor — he is the embodiment of what servant leadership
                        looks like. He shows up for us, listens to us, and delivers. Our community
                        has water, better roads, and hope because of him.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">Fatou Ceesay</div>
                            <div class="testimonial-card__role">Community Leader, Banjul</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card" data-reveal data-reveal-delay="100">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        As a Gambian living abroad, I've watched Talib work tirelessly from a distance.
                        He is the rare leader who remembers the diaspora. He picks up the phone,
                        he listens, and he acts. I am proud to support him.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">Lamin Jallow</div>
                            <div class="testimonial-card__role">Gambian Diaspora, London</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card" data-reveal data-reveal-delay="200">
                    <span class="testimonial-card__mark">"</span>
                    <p class="testimonial-card__text">
                        The youth employment programme changed my life. I went from having no prospects
                        to running my own business within a year. Talib invested in us when nobody
                        else would. That is the kind of leadership The Gambia needs.
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        </div>
                        <div>
                            <div class="testimonial-card__name">Ousman Drammeh</div>
                            <div class="testimonial-card__role">Youth Entrepreneur, Serrekunda</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Upcoming Events ──────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">What's Coming</span>
                <h2 class="section-header__title section-header__title--light">Upcoming Events</h2>
                <p class="section-header__lead section-header__lead--light">
                    Join Talib in person at events across The Gambia and the global diaspora.
                </p>
            </div>

            <div class="events-list">
                @foreach([
                    ['14', 'Jul', 'Diaspora Tour — London',   'Engage the Gambian community in the UK'],
                    ['22', 'Jul', 'Diaspora Tour — New York', 'Meeting the Gambian diaspora on the East Coast'],
                    ['05', 'Aug', 'Diaspora Tour — Madrid',   'Connecting with Gambians across Europe'],
                    ['20', 'Sep', 'Annual Party Convention',  'Banjul, The Gambia — Party manifesto and elections'],
                ] as $i => [$day, $month, $title, $desc])
                <div class="event-row" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="event-row__date">
                        <div class="event-row__date-day">{{ $day }}</div>
                        <div class="event-row__date-month">{{ $month }} 2026</div>
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
                <a href="{{ url('/events') }}" class="btn btn--outline-white">View All Events</a>
            </div>

        </div>
    </section>

    {{-- ── CTA Banner ───────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Ready to make a difference?</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                The Gambia's future is in our hands. Join the movement and help shape the nation
                we want for ourselves and our children.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/contact') }}#join" class="btn btn--navy btn--lg">Join the Party</a>
                <a href="{{ url('/contact') }}#volunteer" class="btn btn--outline-navy btn--lg">Volunteer with Us</a>
            </div>
        </div>
    </section>

</x-app-layout>
