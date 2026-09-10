<x-app-layout>
@php $title = 'About' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">About</span>
                </nav>
                <span class="page-hero__eyebrow">About Talib</span>
                <h1 class="page-hero__title">Talib Ahmed Bensouda</h1>
                <p class="page-hero__subtitle">
                    Lord Mayor of Kanifing Municipal Council.<br>
                    Elected in 2018 at 31 — the youngest mayor in Gambian history.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Bio ────────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="about-bio">

                <div class="about-bio__photo-wrap" data-reveal="fade-right">
                    <div class="about-bio__photo">
                        <div class="about-bio__photo-placeholder">
                            <svg class="about-bio__photo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="5"/>
                                <path d="M3 21c0-5 3.8-9 9-9s9 4 9 9"/>
                            </svg>
                        </div>
                        {{-- Replace with: <img src="/images/talib-portrait.jpg" alt="Talib Bensouda"> --}}
                    </div>
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <span class="about-bio__eyebrow">Biography</span>
                    <h2 class="about-bio__title">From Bakau to City Hall</h2>

                    <p class="about-bio__text">
                        Talib Ahmed Bensouda was born in 1986 in Bakau. He is the son of
                        Amie Bensouda, a prominent Gambian lawyer and former Attorney General,
                        and Ahmed Bensouda, a former Permanent Secretary at the Ministry of
                        Finance. His paternal grandfather was a Moroccan trader who settled
                        in The Gambia in 1913.
                    </p>
                    <p class="about-bio__text">
                        He earned a Bachelor of Arts in Economics and Communication Technology
                        from the University of Toronto in 2007. His early career was spent in
                        sales, management and insurance in Canada and The Gambia — including a
                        role as Marketing Manager at Takaful Gambia Limited — and in 2013 he
                        founded Safari Trading, a company producing and marketing hygiene
                        products, before entering local government.
                    </p>
                    <p class="about-bio__text">
                        In May 2018, at the age of 31, Bensouda was elected Mayor of the
                        Kanifing Municipal Council, defeating Rambo Jatta of the APRC with
                        29,325 votes to become the youngest mayor in Gambian history. He was
                        re-elected in May 2023, defeating Bakary Badjie of the National
                        People's Party. Both campaigns were noted for avoiding partisan
                        attacks and focusing on a development-first manifesto under the
                        slogan "Together for a Better KM."
                    </p>
                    <p class="about-bio__text">
                        As Lord Mayor he has overseen Kanifing's first municipality-wide
                        waste collection system, a €3 million EU-funded environmental
                        transformation programme, more than 38 kilometres of new municipal
                        roads, and the construction of the Kanifing Municipal Library and
                        Innovation Hub. He has since become a national political figure,
                        campaigning on what he calls "The Transformation Agenda." He is
                        married with two children.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Timeline ─────────────────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">The Journey</span>
                <h2 class="section-header__title">Key Milestones</h2>
                <p class="section-header__lead">
                    From the University of Toronto to two terms as Lord Mayor of Kanifing.
                </p>
            </div>

            <div class="timeline">
                @foreach([
                    ['1986', 'Born in Bakau, The Gambia',
                        'Son of Amie Bensouda, former Attorney General, and Ahmed Bensouda, former Permanent Secretary at the Ministry of Finance.'],
                    ['2007', 'Graduates from the University of Toronto',
                        'Earns a Bachelor of Arts in Economics and Communication Technology, then works in sales, management and insurance in Canada and The Gambia.'],
                    ['2013', 'Founds Safari Trading',
                        'Establishes a company producing and marketing hygiene products before moving into local government politics.'],
                    ['2018', 'Elected Lord Mayor of Kanifing',
                        'Wins the KMC mayoral election at 31 with 29,325 votes, defeating Rambo Jatta of the APRC — the youngest mayor in Gambian history.'],
                    ['2019', 'Launches the Mbalit Project',
                        "Kanifing's first structured, municipality-wide waste collection system — a fully funded D130 million partnership with QGroup."],
                    ['2021', 'Begins the Kanifing Environmental Transformation Programme',
                        'Secures a €3 million EU grant, delivered with Peterborough City Council, covering waste management, education and tree planting.'],
                    ['2023', 'Re-elected Lord Mayor of Kanifing',
                        'Returns to office for a second term, defeating Bakary Badjie of the ruling National People\'s Party.'],
                    ['2024', 'Inaugurates the Municipal Library and Innovation Hub',
                        'Opens the D45 million KETP-funded library, career centre and innovation hub; a Local Language Section is added in April 2025.'],
                    ['2025', 'Declares candidacy for the UDP flagbearer position',
                        'Enters the contest to be the United Democratic Party\'s presidential flagbearer ahead of the 2026 election.'],
                    ['2026', 'Emerges as leader of the UNITE Movement for Change',
                        'Begins campaigning nationally on a platform he calls "The Transformation Agenda."'],
                ] as $i => [$year, $title, $desc])
                <div class="tl-item" data-reveal data-reveal-delay="{{ $i * 60 }}">
                    <div class="tl-item__year">{{ $year }}</div>
                    <div class="tl-item__connector">
                        <div class="tl-item__dot"></div>
                        <div class="tl-item__line"></div>
                    </div>
                    <div class="tl-item__content">
                        <h3 class="tl-item__title">{{ $title }}</h3>
                        <p class="tl-item__desc">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── Values ───────────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">How He Governs</span>
                <h2 class="section-header__title">Four Priorities</h2>
                <p class="section-header__lead">
                    The themes that run through Kanifing Municipal Council's work
                    under Talib Bensouda — in the budget, and on the ground.
                </p>
            </div>

            <div class="values-grid">

                <div class="value-card" data-reveal data-reveal-delay="0">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="16"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Delivery</h3>
                    <p class="value-card__desc">Roads, markets, clinics, libraries and a waste system that residents can see and use — projects completed, not just announced.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="80">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Accountability</h3>
                    <p class="value-card__desc">Stronger financial controls, digital property records, and a Council that publishes what it spends and what it builds.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="160">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Environment</h3>
                    <p class="value-card__desc">Waste collection, dumpsite remediation, recycling, flood prevention and a plan to plant 190,000 trees across the municipality's 19 wards.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="240">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Youth &amp; Women</h3>
                    <p class="value-card__desc">A D20 million youth revolving fund, the "Tekki Fii" innovation challenge, skills centres, and financing aimed at women-led enterprise.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ── National role ────────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">
            <div class="party-section">

                <div data-reveal="fade-right">
                    <span class="party-section__eyebrow">National Politics</span>
                    <h2 class="party-section__title">From Kanifing<br>to a National Platform</h2>
                    <p class="party-section__desc">
                        Talib Bensouda served as National Organizing Secretary of the
                        United Democratic Party (UDP). In 2025 he declared his candidacy
                        for the party's presidential flagbearer position, and in 2026 he
                        emerged as leader of the opposition UNITE Movement for Change.
                    </p>
                    <p class="party-section__desc">
                        He is campaigning nationally on what he calls "The Transformation
                        Agenda" — carrying the record built in Kanifing to a wider argument
                        about how The Gambia is governed.
                    </p>
                    <div class="party-section__pillars">
                        <div class="party-section__pillar">Local government that delivers visible results</div>
                        <div class="party-section__pillar">Youth employment and enterprise</div>
                        <div class="party-section__pillar">Environmental and sanitation reform</div>
                        <div class="party-section__pillar">Transparent, well-run public finances</div>
                    </div>
                </div>

                <div data-reveal="fade-left" data-reveal-delay="150">
                    <div class="party-section__logo-wrap">
                        <div class="party-section__logo-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        </div>
                        <div class="party-section__logo-name">The Transformation Agenda</div>
                        <div class="party-section__logo-caption">UNITE Movement for Change · 2026</div>
                        <a href="{{ url('/peoples-mayor') }}" class="btn btn--gold btn--sm" style="margin-top:8px">See the Record</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>See What's Been Built</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                The record speaks for itself — waste systems, roads, markets, clinics
                and a municipal library, delivered across two terms in Kanifing.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--navy btn--lg">The People's Mayor</a>
                <a href="{{ url('/giving-back') }}" class="btn btn--outline-navy btn--lg">Community Support</a>
            </div>
        </div>
    </section>

</x-app-layout>
