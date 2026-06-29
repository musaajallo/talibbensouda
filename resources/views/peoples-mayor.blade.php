<x-app-layout>
@php $title = "The People's Mayor" @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span style="color:rgba(255,255,255,0.65)">The People's Mayor</span>
                </nav>
                <span class="page-hero__eyebrow">Community Impact</span>
                <h1 class="page-hero__title">The People's Mayor</h1>
                <p class="page-hero__subtitle">
                    Not promises — projects. Real investments in real communities,<br>
                    delivered by a Mayor who leads from the front.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Impact Stats ─────────────────────────────────────────────────────── --}}
    <section class="impact-stats">
        <div class="container">
            <div class="impact-stats__grid">
                @foreach([
                    ['50',    'K+',  'Lives Impacted',        50000],
                    ['12',    '+',   'Projects Delivered',    12],
                    ['5',     'K+',  'Households with Water', 5000],
                    ['2,000', '+',   'Jobs Created',          2000],
                ] as $i => [$num, $suffix, $label, $count])
                <div class="impact-stats__item" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <div class="impact-stats__number">
                        <span data-count="{{ $count }}">{{ $num }}</span>
                        <span class="impact-stats__suffix">{{ $suffix }}</span>
                    </div>
                    <div class="impact-stats__label">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Project Showcase ─────────────────────────────────────────────────── --}}
    @php
    $projects = [
        [
            'num'     => '01',
            'tag'     => 'Clean Water',
            'title'   => 'Clean Water Initiative',
            'desc'    => "Access to clean, safe water is a basic human right — not a privilege. Talib Bensouda launched The Gambia's most ambitious community water programme, bringing piped clean water directly to underserved households across the municipality for the first time in their history. The project involved laying kilometres of new pipework, installing community standpipes, and partnering with international NGOs to ensure long-term sustainability.",
            'metrics' => [['5,000+', 'Households Served'], ['18', 'Communities'], ['2', 'Years to Complete']],
            'icon'    => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
            'fill'    => false,
        ],
        [
            'num'     => '02',
            'tag'     => 'Youth Empowerment',
            'title'   => 'Youth Employment Programme',
            'desc'    => "Young Gambians are The Gambia's greatest asset — and Talib's Youth Employment Programme was built on that belief. The scheme provides vocational training, startup grants, and mentorship to young people between the ages of 18 and 35. Participants have gone on to launch businesses in agriculture, technology, construction, and the creative industries. The programme also partners with local companies to create formal employment pathways.",
            'metrics' => [['2,000+', 'Young People Supported'], ['400+', 'Businesses Started'], ['85%', 'Still Trading After 2 Yrs']],
            'icon'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'fill'    => false,
        ],
        [
            'num'     => '03',
            'tag'     => 'Education',
            'title'   => 'Education Infrastructure',
            'desc'    => "Every child in The Gambia deserves to learn in a safe, well-resourced environment. Talib's administration has renovated crumbling school buildings, built new classrooms, provided furniture and teaching materials to schools that had none, and installed solar-powered electricity in rural schools that previously had no lighting. The programme has directly improved conditions for thousands of students and hundreds of teachers.",
            'metrics' => [['22', 'Schools Renovated'], ['8', 'New Schools Built'], ['15,000+', 'Students Benefiting']],
            'icon'    => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
            'fill'    => false,
        ],
        [
            'num'     => '04',
            'tag'     => 'Infrastructure',
            'title'   => 'Roads & Public Spaces',
            'desc'    => "Cracked roads, flooded streets, and broken pavements are not just inconveniences — they hold communities back. Talib's infrastructure programme has repaired and upgraded key roads across the municipality, improved drainage systems to combat seasonal flooding, rehabilitated public parks and squares, and installed street lighting in areas that were previously unlit and unsafe at night.",
            'metrics' => [['120km', 'Roads Upgraded'], ['6', 'Public Spaces Renovated'], ['40%', 'Reduction in Flooding']],
            'icon'    => '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
            'fill'    => false,
        ],
        [
            'num'     => '05',
            'tag'     => "Women's Empowerment",
            'title'   => "Women's Economic Empowerment",
            'desc'    => "Talib Bensouda knows that The Gambia cannot reach its potential without the full participation of its women. The Women's Economic Empowerment Programme provides microloans, business skills training, and market access support to women-led enterprises across the municipality. Women in agriculture, food processing, tailoring, and retail have transformed their livelihoods — and by extension, the wellbeing of their families and communities.",
            'metrics' => [['1,200+', 'Women Supported'], ['GMD 5M+', 'Microloans Disbursed'], ['3', "Women's Business Hubs"]],
            'icon'    => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
            'fill'    => false,
        ],
        [
            'num'     => '06',
            'tag'     => 'Healthcare',
            'title'   => 'Healthcare Access',
            'desc'    => "Too many Gambians have had to travel hours for basic healthcare — or gone without it entirely. Talib's healthcare programme has expanded and upgraded primary health centres across the municipality, equipped facilities with essential medicines and equipment, trained community health workers, and launched mobile health clinics that bring care directly to remote areas.",
            'metrics' => [['8', 'Health Centres Upgraded'], ['3', 'Mobile Clinics Launched'], ['30,000+', 'Patients Served Yearly']],
            'icon'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
            'fill'    => false,
        ],
    ];
    @endphp

    <section class="project-showcase">
        @foreach($projects as $i => $project)
        <div class="project-item" data-reveal data-reveal-delay="{{ $i % 2 === 0 ? 0 : 100 }}">

            <div class="project-item__image">
                <div class="project-item__image-placeholder">
                    <svg width="52" height="52" viewBox="0 0 24 24" fill="{{ $project['fill'] ? 'currentColor' : 'none' }}" stroke="{{ $project['fill'] ? 'none' : 'currentColor' }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        {!! $project['icon'] !!}
                    </svg>
                    <span class="project-item__image-tag">{{ $project['tag'] }}</span>
                </div>
                <div class="project-item__image-num">{{ $project['num'] }}</div>
                {{-- Replace with: <img src="/images/project-{{ $i + 1 }}.jpg" alt="{{ $project['title'] }}"> --}}
            </div>

            <div class="project-item__body">
                <div class="project-item__number">Project {{ $project['num'] }}</div>
                <h2 class="project-item__title">{{ $project['title'] }}</h2>
                <p class="project-item__desc">{{ $project['desc'] }}</p>
                <div class="project-item__metrics">
                    @foreach($project['metrics'] as [$value, $label])
                    <div class="project-item__metric">
                        <span class="project-item__metric-value">{{ $value }}</span>
                        <span class="project-item__metric-label">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
        @endforeach
    </section>

    {{-- ── Pull Quote ───────────────────────────────────────────────────────── --}}
    <div class="mayor-quote">
        <div class="container">
            <p class="mayor-quote__text" data-reveal>
                "Every project we deliver is a promise kept. Not to donors,
                not to parties — to the people of The Gambia."
            </p>
            <div class="mayor-quote__author" data-reveal data-reveal-delay="100">— Talib Bensouda, Mayor</div>
        </div>
    </div>

    {{-- ── Community Photo Grid ─────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Mayor in the Field</span>
                <h2 class="section-header__title">Delivering Change on the Ground</h2>
                <p class="section-header__lead">
                    Talib Bensouda doesn't govern from behind a desk.
                    He is in the communities, at the project sites, alongside the people.
                </p>
            </div>

            @php
            $communityPhotos = [
                ['Water Project',   'Clean Water Launch — Banjul North'],
                ['Youth',           'Youth Employment Graduation'],
                ['Schools',         'School Renovation — Serrekunda'],
                ['Women',           "Women's Business Hub Opening"],
                ['Roads',           'Road Upgrade — Kanifing'],
                ['Health',          'Mobile Clinic — Rural Gambia'],
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

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Back the Mayor Who Delivers</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                These projects were built by a Mayor who puts the people first.
                Help us continue the work — join the movement today.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/contact') }}#join" class="btn btn--navy btn--lg">Join the Party</a>
                <a href="{{ url('/giving-back') }}" class="btn btn--outline-navy btn--lg">See How We Give Back</a>
            </div>
        </div>
    </section>

</x-app-layout>
