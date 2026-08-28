<x-app-layout>
@php $title = "The People's Mayor" @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">The People's Mayor</span>
                </nav>
                <span class="page-hero__eyebrow">The Record · 2018 – 2026</span>
                <h1 class="page-hero__title">The People's Mayor</h1>
                <p class="page-hero__subtitle">
                    Kanifing Municipal Council's work under Talib Bensouda —<br>
                    waste, roads, markets, health, youth and women.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Impact Stats ─────────────────────────────────────────────────────── --}}
    <section class="impact-stats">
        <div class="container">
            <div class="impact-stats__grid">
                @foreach([
                    ['130',    'M',   'Mbalit Waste Project (GMD)',   130],
                    ['38',     'km',  'New Roads Across 19 Wards',     38],
                    ['172',    '',    'Youth Employed — Mbalit',       172],
                    ['31,867', '',    'Properties Digitally Addressed', 31867],
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
            'tag'     => 'Waste Management',
            'title'   => 'The Mbalit Project',
            'desc'    => "Kanifing's first structured, municipality-wide waste collection system — and the first of its kind in the sub-region. Delivered as a three-year, fully funded D130 million partnership with QGroup, the project introduced organised household waste collection with 24 new compactor trucks, skip trucks, septic emptiers and tipper trucks. The Council paid off the facility in full and secured ownership of all vehicles, and employed 172 young people as janitors, drivers, ticket agents and secretaries.",
            'metrics' => [['D130M', 'Fully Funded'], ['24', 'Compactor Trucks'], ['172', 'Youth Jobs']],
            'icon'    => '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
            'fill'    => false,
        ],
        [
            'num'     => '02',
            'tag'     => 'Environment',
            'title'   => 'Kanifing Environmental Transformation Programme',
            'desc'    => "KMC's flagship environmental programme, funded by a €3 million European Union grant and delivered in partnership with Peterborough City Council in the UK. KETP covers waste management, environmental education and tree planting. It funded the Kanifing Municipal Library and Innovation Hub, the \"one garbage can per household\" rollout of 10,000 bins, community transfer stations for waste sorting, recycling infrastructure at Bakoteh, and a plan to plant 190,000 trees across the municipality's 19 wards.",
            'metrics' => [['€3M', 'EU Grant'], ['10,000', 'Bins Distributed'], ['190,000', 'Trees Planned']],
            'icon'    => '<path d="M12 22V8"/><path d="M12 8a4 4 0 0 0-4-4 4 4 0 0 0 4 8 4 4 0 0 0 4-8 4 4 0 0 0-4 4z"/>',
            'fill'    => false,
        ],
        [
            'num'     => '03',
            'tag'     => 'Roads &amp; Drainage',
            'title'   => 'Kanifing Municipal Road Network Project',
            'desc'    => "A Council-funded programme of more than D300 million to build 38 kilometres of new roads connecting KMC's 19 wards — including 11 feeder roads, two bridges and 5.9 kilometres of new drainage. It builds on earlier Council road works on Lat Kumba Road, Bakau Marina Road, and the Latrikunda–Wellingara and Kololi–Manjai roads, and complements national road efforts by the OIC and NRA.",
            'metrics' => [['D300M+', 'Council-Funded'], ['38km', 'New Roads'], ['19', 'Wards Connected']],
            'icon'    => '<path d="M4 19l4-14"/><path d="M20 19L16 5"/><path d="M12 5v3"/><path d="M12 12v3"/><path d="M12 19v0"/>',
            'fill'    => false,
        ],
        [
            'num'     => '04',
            'tag'     => 'Markets',
            'title'   => 'Market Construction &amp; Rehabilitation',
            'desc'    => "New and rehabilitated markets to give vendors — particularly women traders — safe, dignified space to earn a living. Latrikunda Sabiji Market was rebuilt as a storey building with 100 shops. Serrekunda Market received a major upgrade with a 30-kiosk women's shed, rehabilitated drains, CCTV, boreholes and 60 security lights. Markets at Tallinding, Old Bakau and Latrikunda Yiriganya were rehabilitated, and a Council-owned company, Kanifing Municipal Markets Ltd, is expanding the network further.",
            'metrics' => [['100', 'Shops at Latrikunda Sabiji'], ['19 &rarr; 26', 'Markets Planned'], ['30', 'Women\'s Kiosks — Serrekunda']],
            'icon'    => '<path d="M3 9l1-5h16l1 5"/><path d="M4 9v11a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9"/><path d="M9 21v-6h6v6"/>',
            'fill'    => false,
        ],
        [
            'num'     => '05',
            'tag'     => 'Health',
            'title'   => 'Healthcare Access',
            'desc'    => "KMC expanded the Bundung Maternity Ward in a D15 million partnership with Gamworks, rehabilitated the Council's maternity ward at Serekunda Hospital, and provided support to community clinics in Ebo Town and Tallinding. Nine ambulances were provided to nine community clinics across Kanifing Municipality so that emergencies could be reached faster.",
            'metrics' => [['9', 'Ambulances to 9 Clinics'], ['D15M', 'Bundung Maternity Ward'], ['3', 'Community Clinics Supported']],
            'icon'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
            'fill'    => false,
        ],
        [
            'num'     => '06',
            'tag'     => 'Youth &amp; Women',
            'title'   => 'Jobs, Skills and Enterprise',
            'desc'    => "A D20 million Youth Revolving Fund provides soft loans to young entrepreneurs. The Mayor's \"Tekki Fii\" Innovative Challenge, run with the EU and the Youth Empowerment Project, has awarded startup grants to Gambian innovators. The Bakoteh Production and Innovation Centre, opened in December 2022, supports training and production in textiles and hand-woven goods, and 155 sewing machines were distributed to skills and community centres — alongside financing and cold-storage facilities aimed at women-led enterprise.",
            'metrics' => [['D20M', 'Youth Revolving Fund'], ['155', 'Sewing Machines'], ['D100M', 'Toward Women\'s Enterprise']],
            'icon'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
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
                    <span class="project-item__image-tag">{!! $project['tag'] !!}</span>
                </div>
                <div class="project-item__image-num">{{ $project['num'] }}</div>
                {{-- Replace with: <img src="/images/project-{{ $i + 1 }}.jpg" alt="{{ strip_tags($project['title']) }}"> --}}
            </div>

            <div class="project-item__body">
                <div class="project-item__number">Project {{ $project['num'] }}</div>
                <h2 class="project-item__title">{!! $project['title'] !!}</h2>
                <p class="project-item__desc">{{ $project['desc'] }}</p>
                <div class="project-item__metrics">
                    @foreach($project['metrics'] as [$value, $label])
                    <div class="project-item__metric">
                        <span class="project-item__metric-value">{!! $value !!}</span>
                        <span class="project-item__metric-label">{!! $label !!}</span>
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
                Council revenue rose from D115 million in 2017 to D332 million in 2022 —
                and every dalasi of it is meant to show up as something residents can use.
            </p>
            <div class="mayor-quote__author" data-reveal data-reveal-delay="100">— Kanifing Municipal Council, 2018–2022 record</div>
        </div>
    </div>

    {{-- ── Community Photo Grid ─────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">On the Ground</span>
                <h2 class="section-header__title">Where the Work Happens</h2>
                <p class="section-header__lead">
                    Kanifing's 19 wards — from Bakau and Bakoteh to Latrikunda,
                    Tallinding and Serrekunda.
                </p>
            </div>

            @php
            $communityPhotos = [
                ['Waste',       'Mbalit Collection — Kanifing'],
                ['Environment', 'Bakoteh Dumpsite Remediation'],
                ['Library',     'Municipal Library &amp; Innovation Hub'],
                ['Markets',     'Serrekunda Market Upgrade'],
                ['Roads',       'Road Network Project — Latrikunda'],
                ['Youth',       'Bakoteh Production &amp; Innovation Centre'],
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
                        <span class="community-photo__tag">{!! $tag !!}</span>
                        <p class="community-photo__caption">{!! $caption !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── Note on figures ──────────────────────────────────────────────────── --}}
    <section class="section section--tight">
        <div class="container">
            <p style="max-width:760px; margin:0 auto; font-size:0.85rem; color:var(--muted); text-align:center;">
                Figures are drawn from Kanifing Municipal Council records and public
                reporting. Council-reported financial figures for 2018–2022 are
                self-reported; several KMC market, road and library projects were
                independently verified by a Dubawa fact-check in December 2025.
            </p>
        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>The Full Picture</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                Beyond the headline projects, the Council invests directly in wards,
                elders, faith communities, sport and women's livelihoods.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/giving-back') }}" class="btn btn--navy btn--lg">Community Support</a>
                <a href="{{ url('/events') }}" class="btn btn--outline-navy btn--lg">Events &amp; Milestones</a>
            </div>
        </div>
    </section>

</x-app-layout>
