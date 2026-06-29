<x-app-layout>
@php $title = 'Giving Back' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span style="color:rgba(255,255,255,0.65)">Giving Back</span>
                </nav>
                <span class="page-hero__eyebrow">Community Service</span>
                <h1 class="page-hero__title">Giving Back</h1>
                <p class="page-hero__subtitle">
                    Beyond the official role — Talib Bensouda's personal commitment
                    to uplifting every Gambian community, one act of service at a time.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Mission Intro ────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="mission-intro">

                <div data-reveal="fade-right">
                    <span class="mission-intro__eyebrow">Why Giving Back Matters</span>
                    <h2 class="mission-intro__title">Service Is Not a Duty —<br>It Is a Way of Life</h2>
                    <p class="mission-intro__text">
                        Talib Bensouda has always believed that leadership without
                        generosity is incomplete. Long before he held any official title,
                        he was feeding families, mentoring young people, and investing in the
                        communities around him. That spirit has never left him.
                    </p>
                    <p class="mission-intro__text">
                        His giving back programmes operate independently of government funding —
                        driven entirely by personal commitment, community partnerships, and the
                        support of fellow Gambians who share his vision of a nation where
                        no one is left behind.
                    </p>
                    <blockquote class="mission-intro__quote">
                        "My position is a privilege. With that privilege comes a responsibility
                        that does not end when the office closes."
                        <footer style="margin-top:8px; font-size:0.82rem; font-style:normal; color:var(--muted);">— Talib Bensouda</footer>
                    </blockquote>
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <div class="mission-intro__stats">

                        <div class="mission-stat" data-reveal data-reveal-delay="0">
                            <div class="mission-stat__value">5,000<span style="font-size:0.6em">+</span></div>
                            <div class="mission-stat__label">Families supported through giving back initiatives</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="80">
                            <div class="mission-stat__value">200<span style="font-size:0.6em">+</span></div>
                            <div class="mission-stat__label">Scholarships awarded to young Gambians</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="160">
                            <div class="mission-stat__value">12<span style="font-size:0.6em">+</span></div>
                            <div class="mission-stat__label">Years of personal community service</div>
                        </div>

                        <div class="mission-stat" data-reveal data-reveal-delay="240">
                            <div class="mission-stat__value">30<span style="font-size:0.6em">+</span></div>
                            <div class="mission-stat__label">Community partnerships across The Gambia</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Initiatives ──────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">What We Do</span>
                <h2 class="section-header__title">Our Giving Back Programmes</h2>
                <p class="section-header__lead">
                    Six ongoing initiatives, each targeting a different area of need —
                    all rooted in the same belief that every Gambian deserves dignity and opportunity.
                </p>
            </div>

            <div class="initiatives-grid">

                @foreach([
                    [
                        'title'  => 'Ramadan Feeding Programme',
                        'desc'   => 'Every year during Ramadan, Talib organises the distribution of cooked iftar meals and dry food parcels to families in need across the municipality. No one should go hungry during the holy month.',
                        'metric' => '2,000+ families fed annually',
                        'delay'  => 0,
                        'icon'   => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
                    ],
                    [
                        'title'  => 'Back to School Drive',
                        'desc'   => 'Each September, the team distributes school bags, textbooks, stationery, and uniforms to children from low-income families — removing the financial barrier that keeps too many children out of the classroom.',
                        'metric' => '1,500+ children supported yearly',
                        'delay'  => 80,
                        'icon'   => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                    ],
                    [
                        'title'  => 'Scholarship Programme',
                        'desc'   => 'Talib personally funds annual scholarships for bright young Gambians who cannot afford secondary school or university fees. Recipients are selected based on academic merit and financial need.',
                        'metric' => '200+ scholarships awarded',
                        'delay'  => 160,
                        'icon'   => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>',
                    ],
                    [
                        'title'  => 'Elderly Care & Support',
                        'desc'   => 'A monthly programme providing food packages, household essentials, and welfare visits to elderly residents in the community who live alone or without family support. Because our elders deserve respect in action, not just words.',
                        'metric' => '300+ elderly people supported',
                        'delay'  => 0,
                        'icon'   => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                    ],
                    [
                        'title'  => 'Youth Sports & Recreation',
                        'desc'   => 'Funding football kits, sports equipment, and recreational facilities for youth teams across the municipality. Sport builds discipline, teamwork, and community — and it gives young people something positive to invest their energy in.',
                        'metric' => '50+ youth teams supported',
                        'delay'  => 80,
                        'icon'   => '<circle cx="12" cy="12" r="10"/><path d="M4.93 4.93l4.24 4.24"/><path d="M14.83 9.17l4.24-4.24"/><path d="M14.83 14.83l4.24 4.24"/><path d="M9.17 14.83l-4.24 4.24"/><circle cx="12" cy="12" r="4"/>',
                    ],
                    [
                        'title'  => 'Community Clean-Up & Beautification',
                        'desc'   => 'Organising neighbourhood clean-ups, tree planting, mural painting, and public space beautification projects across the municipality. A clean, beautiful community is one that people take pride in and are willing to protect.',
                        'metric' => '40+ community clean-ups held',
                        'delay'  => 160,
                        'icon'   => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
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
                    ['5',   'K+',  'Families Supported',     5000],
                    ['200', '+',   'Scholarships Awarded',    200],
                    ['50',  '+',   'Youth Teams Funded',      50],
                    ['30',  '+',   'Community Partners',      30],
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
                <span class="section-header__eyebrow">In the Community</span>
                <h2 class="section-header__title">Giving Back in Action</h2>
                <p class="section-header__lead">
                    These moments speak for themselves — Talib Bensouda and his team,
                    showing up for the people of The Gambia.
                </p>
            </div>

            @php
            $photos = [
                ['#0d1b38', 'Ramadan',    'Iftar Distribution — Banjul 2025'],
                ['#0a1931', 'Education',  'Back to School Drive — Serrekunda'],
                ['#112044', 'Education',  'Scholarship Ceremony 2024'],
                ['#091422', 'Elderly',    'Elderly Support Visit — Bakau'],
                ['#0f2040', 'Youth',      'Youth Football — Kanifing'],
                ['#0d1b38', 'Community',  'Neighbourhood Clean-Up 2025'],
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

    {{-- ── Community Stories ────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">People's Voices</span>
                <h2 class="section-header__title">Stories from the Community</h2>
                <p class="section-header__lead">
                    The real measure of giving back is not in numbers —
                    it is in the lives it touches.
                </p>
            </div>

            <div class="stories-grid">

                <div class="story-card" data-reveal data-reveal-delay="0">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#0d1b38,#112044);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="5"/><path d="M3 21c0-5 3.8-9 9-9s9 4 9 9"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            When my children were going to miss school because we couldn't
                            afford their books, someone from Talib's team showed up with everything
                            they needed. My children are still in school today because of that.
                        </p>
                        <div class="story-card__name">Mariama Jobe</div>
                        <div class="story-card__role">Mother of three, Serrekunda</div>
                    </div>
                </div>

                <div class="story-card" data-reveal data-reveal-delay="100">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#0a1931,#0d1b38);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="5"/><path d="M3 21c0-5 3.8-9 9-9s9 4 9 9"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            I received a scholarship from Talib Bensouda when I was 17.
                            I am now finishing my degree in engineering. Without that support
                            at the right moment, I don't know where I would be.
                        </p>
                        <div class="story-card__name">Alieu Sanneh</div>
                        <div class="story-card__role">Engineering student, University of The Gambia</div>
                    </div>
                </div>

                <div class="story-card" data-reveal data-reveal-delay="200">
                    <div class="story-card__image" style="background: linear-gradient(135deg,#112044,#091422);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="5"/><path d="M3 21c0-5 3.8-9 9-9s9 4 9 9"/></svg>
                    </div>
                    <div class="story-card__body">
                        <p class="story-card__quote">
                            During Ramadan, his team came to our neighbourhood with food for
                            everyone — young, old, rich and poor. He didn't make a show of it.
                            He just did it. That is the kind of man he is.
                        </p>
                        <div class="story-card__name">Ousman Bah</div>
                        <div class="story-card__role">Community Elder, Banjul North</div>
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
                    Giving back is not a one-person job. Join us and help build
                    a community where everyone looks out for each other.
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
                        Join a distribution team, help organise a clean-up,
                        mentor a young person, or assist at a community event.
                        Every hour of your time makes a difference.
                    </p>
                    <a href="{{ url('/contact') }}#volunteer" class="btn btn--gold btn--sm">Become a Volunteer</a>
                </div>

                <div class="help-card" data-reveal data-reveal-delay="100">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <h3 class="help-card__title">Donate Supplies or Funds</h3>
                    <p class="help-card__desc">
                        School supplies, food items, sports equipment, or financial contributions
                        — all donations go directly to the programmes. Nothing is wasted.
                        Every dalasi counts.
                    </p>
                    <a href="{{ url('/contact') }}#donate" class="btn btn--gold btn--sm">Make a Donation</a>
                </div>

                <div class="help-card" data-reveal data-reveal-delay="200">
                    <div class="help-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                        </svg>
                    </div>
                    <h3 class="help-card__title">Spread the Word</h3>
                    <p class="help-card__desc">
                        Share these initiatives on social media, tell your network, and help
                        connect people in need with the support that is available to them.
                        Awareness is the first step.
                    </p>
                    <a href="{{ url('/contact') }}" class="btn btn--gold btn--sm">Get in Touch</a>
                </div>

            </div>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Be Part of Something Greater</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                The Gambia rises when its people rise together.
                Join Talib Bensouda's movement and help build the nation we all deserve.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/contact') }}#join" class="btn btn--navy btn--lg">Join the Party</a>
                <a href="{{ url('/contact') }}#volunteer" class="btn btn--outline-navy btn--lg">Volunteer with Us</a>
            </div>
        </div>
    </section>

</x-app-layout>
