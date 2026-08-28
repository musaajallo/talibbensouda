<x-app-layout>
@php $title = 'Community Support' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Community Support</span>
                </nav>
                <span class="page-hero__eyebrow">Beyond the Big Projects</span>
                <h1 class="page-hero__title">Community Support</h1>
                <p class="page-hero__subtitle">
                    The everyday side of the Council's work — ward development funds,
                    support for elders and faith communities, sport, scholarships
                    and women's livelihoods.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Intro ────────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="mission-intro">

                <div data-reveal="fade-right">
                    <span class="mission-intro__eyebrow">The Approach</span>
                    <h2 class="mission-intro__title">Money That Reaches<br>the Ward Level</h2>
                    <p class="mission-intro__text">
                        Alongside the large infrastructure projects, Kanifing Municipal
                        Council channels funding directly to its 19 wards — through Ward
                        Development Committees, community heads, faith institutions, youth
                        teams and women's gardens.
                    </p>
                    <p class="mission-intro__text">
                        Each Ward Development Committee receives an annual development fund,
                        and the Council has opened, furnished and paid rent on a dedicated
                        office in every ward. Community heads (Alkalis) receive stipends,
                        and the Council contributes to mosque refurbishment and religious
                        gatherings each year.
                    </p>
                    <blockquote class="mission-intro__quote">
                        "Together for a Better KM."
                        <footer style="margin-top:8px; font-size:0.82rem; font-style:normal; color:var(--muted);">— Kanifing Municipal Council campaign slogan</footer>
                    </blockquote>
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <div class="mission-intro__stats">

                        <div class="mission-stat" data-reveal data-reveal-delay="0">
                            <div class="mission-stat__value">D200<span style="font-size:0.6em">k</span></div>
                            <div class="mission-stat__label">Per ward, per year, to each Ward Development Committee</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="80">
                            <div class="mission-stat__value">19</div>
                            <div class="mission-stat__label">Ward Development Committee offices opened and equipped</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="160">
                            <div class="mission-stat__value">D6<span style="font-size:0.6em">M+</span></div>
                            <div class="mission-stat__label">In scholarships to KM students over six years</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="240">
                            <div class="mission-stat__value">155</div>
                            <div class="mission-stat__label">Sewing machines distributed to skills and community centres</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Programmes ───────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Where It Goes</span>
                <h2 class="section-header__title">Community Programmes</h2>
                <p class="section-header__lead">
                    Six strands of direct community investment, drawn from Council records.
                </p>
            </div>

            <div class="initiatives-grid">

                @foreach([
                    [
                        'title'  => 'Ward Development Funds',
                        'desc'   => 'Every one of the 19 Ward Development Committees receives an annual development fund of D200,000. The Council has also opened, furnished and paid the rent on a WDC office in each ward, equipped with computers, furniture and prepaid power.',
                        'metric' => 'D3.8M to WDCs per year',
                        'delay'  => 0,
                        'icon'   => '<path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>',
                    ],
                    [
                        'title'  => 'Support for Alkalis',
                        'desc'   => 'Sixteen community heads (Alkalis) across Kanifing Municipality receive stipends from the Council, recognising their role in local dispute resolution, land matters and day-to-day community leadership.',
                        'metric' => 'D2.2M to Alkalis per year',
                        'delay'  => 80,
                        'icon'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/>',
                    ],
                    [
                        'title'  => 'Religious Support',
                        'desc'   => 'The Council contributes around D1 million a year toward mosque refurbishment and around D1.5 million a year toward religious gatherings such as Gamos and conferences, alongside regular visits to worship with congregations.',
                        'metric' => '~D2.5M per year',
                        'delay'  => 160,
                        'icon'   => '<path d="M12 2v20"/><path d="M5 8h14"/><path d="M6 22V10l6-4 6 4v12"/>',
                    ],
                    [
                        'title'  => 'Scholarships & School Support',
                        'desc'   => 'More than D6 million in scholarships to Kanifing Municipality students over six years, plus over D12 million in grants, scholarships and donations to KM schools — and an annual D2.4 million subvention to Charles Jow Memorial Academy.',
                        'metric' => 'D6M+ in scholarships / 6 yrs',
                        'delay'  => 0,
                        'icon'   => '<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1 2 3 6 3s6-2 6-3v-5"/>',
                    ],
                    [
                        'title'  => 'Sport & Recreation',
                        'desc'   => 'The Mayor\'s Trophy football tournament costs around D1 million to run, with D350,000 paid out in prizes to community teams. A further D500,000 has gone toward Gambian athletics, and buffer-zone pitches have been upgraded with new goalposts and 180 solar lights.',
                        'metric' => 'D350k in community team prizes',
                        'delay'  => 80,
                        'icon'   => '<circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20"/><path d="M2 12h20"/><circle cx="12" cy="12" r="3"/>',
                    ],
                    [
                        'title'  => "Women's Livelihoods",
                        'desc'   => 'Cold-storage facilities for women vendors at Serrekunda Market and for Denton Bridge oyster sellers; boreholes for the Bakoteh Women\'s Garden; compost machinery at the Bakau Women\'s Garden; and 155 sewing machines for women in tailoring alongside youth.',
                        'metric' => 'D100M toward women\'s enterprise',
                        'delay'  => 160,
                        'icon'   => '<circle cx="12" cy="8" r="5"/><path d="M12 13v8"/><path d="M9 18h6"/>',
                    ],
                ] as $item)
                <div class="initiative-card" data-reveal data-reveal-delay="{{ $item['delay'] }}">
                    <div class="initiative-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            {!! $item['icon'] !!}
                        </svg>
                    </div>
                    <h3 class="initiative-card__title">{{ $item['title'] }}</h3>
                    <p class="initiative-card__desc">{{ $item['desc'] }}</p>
                    <span class="initiative-card__metric">{{ $item['metric'] }}</span>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ── Impact Stats ─────────────────────────────────────────────────────── --}}
    <section class="giving-stats">
        <div class="container">
            <div class="giving-stats__grid">
                @foreach([
                    ['19',  '',   'Ward Development Offices',  19],
                    ['16',  '',   'Alkalis Supported',         16],
                    ['180', '',   'Solar Lights — Buffer Zone', 180],
                    ['155', '',   'Sewing Machines Distributed', 155],
                ] as $i => [$num, $suffix, $label, $count])
                <div class="giving-stats__item" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="giving-stats__number">
                        <span data-count="{{ $count }}">{{ $num }}</span>
                        <span class="giving-stats__suffix">{{ $suffix }}</span>
                    </div>
                    <div class="giving-stats__label">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Community Photos ─────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">In the Wards</span>
                <h2 class="section-header__title">Community Support in Action</h2>
                <p class="section-header__lead">
                    Ward offices, women's gardens, community centres and youth pitches
                    across Kanifing.
                </p>
            </div>

            @php
            $photos = [
                ['#0d1b38', 'Wards',    'Ward Development Committee office'],
                ['#0a1931', 'Schools',  'Scholarship support for KM students'],
                ['#112044', 'Faith',    'Mosque refurbishment support'],
                ['#091422', 'Women',    "Bakau Women's Garden — boreholes & compost"],
                ['#0f2040', 'Sport',    "Mayor's Trophy community tournament"],
                ['#0d1b38', 'Skills',   'Sewing machines for community centres'],
            ];
            @endphp

            <div class="giving-photos">
                @foreach($photos as $i => [$bg, $tag, $caption])
                <div class="giving-photo" style="background-color:{{ $bg }};" data-reveal data-reveal-delay="{{ $i * 70 }}">
                    <div class="giving-photo__placeholder" style="background-color:{{ $bg }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    {{-- Replace with: <img src="/images/giving/{{ $i + 1 }}.jpg" alt="{{ $caption }}"> --}}
                    <div class="giving-photo__overlay">
                        <div class="giving-photo__tag">{{ $tag }}</div>
                        <p class="giving-photo__caption">{{ $caption }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── Municipal enterprises ────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Run at Arm's Length</span>
                <h2 class="section-header__title">Municipal Enterprises</h2>
                <p class="section-header__lead">
                    The Council set up two limited liability companies to deliver services
                    separately from core administration.
                </p>
            </div>

            <div class="stories-grid">

                <div class="story-card" data-reveal data-reveal-delay="0">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#0d1b38,#112044);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h4l3 3v4h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            Kanifing Municipal Transport (KMT) was established to provide
                            affordable, accessible bus services for residents, with
                            provision for the elderly and differently abled.
                        </p>
                        <div class="story-card__name">Kanifing Municipal Transport</div>
                        <div class="story-card__role">Council-owned company</div>
                    </div>
                </div>

                <div class="story-card" data-reveal data-reveal-delay="100">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#0a1931,#0d1b38);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9"/><path d="M9 21v-6h6v6"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            Kanifing Municipal Markets (KMM) develops and manages the
                            municipality's expanding market network, with projects at Faji
                            Kunda, Abuko, Bundung Jola Kunda, Mbar Pa Dembo and Bakoteh.
                        </p>
                        <div class="story-card__name">Kanifing Municipal Markets</div>
                        <div class="story-card__role">Council-owned company</div>
                    </div>
                </div>

                <div class="story-card" data-reveal data-reveal-delay="200">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#112044,#091422);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            Municipal Police grew from 42 to 200 personnel, resourced with
                            new pickups, uniforms, motorbikes and training at the Gambia
                            Police School, alongside KMC's first comprehensive by-laws.
                        </p>
                        <div class="story-card__name">Municipal Police &amp; By-laws</div>
                        <div class="story-card__role">Enforcement reform</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── How to Help ──────────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Get Involved</span>
                <h2 class="section-header__title section-header__title--light">How You Can Help</h2>
                <p class="section-header__lead section-header__lead--light">
                    Community work is never a one-person job. There are a few ways
                    to be part of it.
                </p>
            </div>

            <div class="help-grid">

                <div class="help-card" data-reveal data-reveal-delay="0">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="help-card__title">Volunteer Your Time</h3>
                    <p class="help-card__desc">
                        Help at a community event, a clean-up or a school drive.
                        Get in touch and the team will point you to where you're needed.
                    </p>
                    <a href="{{ url('/contact') }}#volunteer" class="btn btn--gold btn--sm">Volunteer</a>
                </div>

                <div class="help-card" data-reveal data-reveal-delay="100">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                        </svg>
                    </div>
                    <h3 class="help-card__title">Share the Record</h3>
                    <p class="help-card__desc">
                        Tell people what's been built in Kanifing. Point them to the
                        record and let them judge it for themselves.
                    </p>
                    <a href="{{ url('/peoples-mayor') }}" class="btn btn--gold btn--sm">See the Record</a>
                </div>

                <div class="help-card" data-reveal data-reveal-delay="200">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <h3 class="help-card__title">Get in Touch</h3>
                    <p class="help-card__desc">
                        Questions, partnership ideas or press enquiries — send a message
                        and someone from the team will respond.
                    </p>
                    <a href="{{ url('/contact') }}" class="btn btn--gold btn--sm">Contact the Team</a>
                </div>

            </div>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>The Bigger Projects</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                Ward-level support sits alongside the Council's flagship work on waste,
                roads, markets, health and the municipal library.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--navy btn--lg">The People's Mayor</a>
                <a href="{{ url('/contact') }}" class="btn btn--outline-navy btn--lg">Get in Touch</a>
            </div>
        </div>
    </section>

</x-app-layout>
