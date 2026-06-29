<x-app-layout>
@php $title = 'About' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span style="color:rgba(255,255,255,0.65)">About</span>
                </nav>
                <span class="page-hero__eyebrow">About Talib</span>
                <h1 class="page-hero__title">Talib Bensouda</h1>
                <p class="page-hero__subtitle">
                    Mayor. Party Leader. Son of The Gambia.<br>
                    A life committed to the people he serves.
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
                    <h2 class="about-bio__title">A Life in Service of The Gambia</h2>

                    <p class="about-bio__text">
                        Talib Bensouda is a visionary leader, Mayor, and party leader who has dedicated
                        his life to the service of The Gambia. Born and raised in Banjul, he developed
                        a deep understanding of the challenges facing ordinary Gambians — from inadequate
                        infrastructure and limited economic opportunity to inequality in healthcare
                        and education.
                    </p>
                    <p class="about-bio__text">
                        After years working in community development and civic affairs, Talib entered
                        politics with a clear mandate: to serve the people, not the system. He rose
                        through the ranks on the strength of his work ethic, his integrity, and his
                        ability to connect with Gambians from every walk of life — in villages and
                        cities alike, and across the global diaspora.
                    </p>
                    <p class="about-bio__text">
                        As Mayor, Talib has overseen a period of unprecedented community development —
                        delivering clean water to thousands of households, launching youth employment
                        schemes, renovating schools, improving healthcare access, and upgrading roads
                        and public spaces. Each project has been guided by one principle: the people
                        come first.
                    </p>
                    <p class="about-bio__text">
                        As party leader, he is building a national movement rooted in unity,
                        accountability, and real development — one that belongs not to politicians,
                        but to every Gambian willing to play their part.
                    </p>

                    <blockquote class="about-bio__quote">
                        <p>"The Gambia does not belong to politicians. It belongs to every man,
                        woman, and child who calls it home. My job is simply to serve them."</p>
                    </blockquote>
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
                    From community activist to Mayor and national party leader —
                    the road that shaped Talib Bensouda.
                </p>
            </div>

            <div class="timeline">
                @foreach([
                    ['1985', 'Born in Banjul, The Gambia',
                        'Talib grew up in Banjul, the capital, surrounded by community life and the everyday realities of Gambian society.'],
                    ['2003', 'Community Development Work',
                        'Began working with grassroots organisations to address sanitation, youth unemployment, and local infrastructure.'],
                    ['2009', 'Founded Youth Leadership Network',
                        'Launched a youth organisation that trained over 2,000 young Gambians in civic leadership and entrepreneurship.'],
                    ['2015', 'Elected to Local Government',
                        'Won his first election and quickly became known for his hands-on approach and refusal to engage in corruption.'],
                    ['2018', 'Founded Political Party',
                        'Established a new political movement built on the principles of unity, accountability, and grassroots development.'],
                    ['2020', 'Elected Mayor',
                        'Won the mayoral election with a strong mandate, becoming the voice of the people in local government.'],
                    ['2022', 'Re-elected Mayor',
                        'Returned to office with an increased majority following visible improvements in infrastructure and community wellbeing.'],
                    ['2024', 'National Party Expansion',
                        'Launched nationwide party growth and strengthened ties with the Gambian diaspora across Europe and North America.'],
                    ['2026', 'Diaspora Tour Launched',
                        'Embarked on a landmark tour engaging Gambians in the UK, USA, and Europe ahead of the national campaign.'],
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
                <span class="section-header__eyebrow">What He Stands For</span>
                <h2 class="section-header__title">Core Values</h2>
                <p class="section-header__lead">
                    Four pillars that guide every decision Talib Bensouda makes
                    as Mayor, party leader, and servant of the Gambian people.
                </p>
            </div>

            <div class="values-grid">

                <div class="value-card" data-reveal data-reveal-delay="0">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Unity</h3>
                    <p class="value-card__desc">Building bridges across communities, regions, and the diaspora. One Gambia, one people, one shared destiny.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="80">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Accountability</h3>
                    <p class="value-card__desc">A government that is transparent, honest, and answerable to the people it serves — not to powerful interests.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="160">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="16"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Development</h3>
                    <p class="value-card__desc">Real infrastructure, real jobs, real change — projects that ordinary Gambians can see, touch, and benefit from.</p>
                </div>

                <div class="value-card" data-reveal data-reveal-delay="240">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title">Inclusion</h3>
                    <p class="value-card__desc">Every Gambian — regardless of background, tribe, gender, or location — deserves opportunity, dignity, and a voice.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Party ────────────────────────────────────────────────────────────── --}}
    <section class="section section--navy">
        <div class="container">
            <div class="party-section">

                <div data-reveal="fade-right">
                    <span class="party-section__eyebrow">Political Party</span>
                    <h2 class="party-section__title">Leading a Movement,<br>Not Just a Party</h2>
                    <p class="party-section__desc">
                        Talib Bensouda leads a national political movement founded on the belief
                        that The Gambia's transformation must come from the ground up. The party
                        is not built around a single person — it is built around a shared vision
                        for every Gambian citizen.
                    </p>
                    <p class="party-section__desc">
                        With branches across the country and active diaspora chapters in Europe
                        and North America, the movement is growing rapidly — powered by ordinary
                        Gambians who believe change is possible.
                    </p>
                    <div class="party-section__pillars">
                        <div class="party-section__pillar">Democratic governance rooted in the people</div>
                        <div class="party-section__pillar">Economic development and youth empowerment</div>
                        <div class="party-section__pillar">Diaspora inclusion and engagement</div>
                        <div class="party-section__pillar">Social justice and equal opportunity</div>
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
                        <div class="party-section__logo-name">Party Name</div>
                        <div class="party-section__logo-caption">Add party logo and name here</div>
                        <a href="{{ url('/contact') }}" class="btn btn--gold btn--sm" style="margin-top:8px">Join the Party</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Stand with Talib Bensouda</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                Join thousands of Gambians — at home and abroad — who are working
                together to build the future The Gambia deserves.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/contact') }}#join" class="btn btn--navy btn--lg">Join the Party</a>
                <a href="{{ url('/contact') }}#volunteer" class="btn btn--outline-navy btn--lg">Become a Volunteer</a>
            </div>
        </div>
    </section>

</x-app-layout>
