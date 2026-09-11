<x-app-layout title="Events" styles="events">

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Events</span>
                </nav>
                <span class="page-hero__eyebrow">Events &amp; Milestones</span>
                <h1 class="page-hero__title">Events &amp; Milestones</h1>
                <p class="page-hero__subtitle">
                    A record of the launches, openings and gatherings that have marked
                    Talib Bensouda's work as Lord Mayor of Kanifing.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Intro ──────────────────────────────────────────────────────────── --}}
    <section class="section section--tight">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">2018 – 2026</span>
                <h2 class="section-header__title">Delivered, Opened, Launched</h2>
                <p class="section-header__lead">
                    The dates below are drawn from Kanifing Municipal Council records and
                    public reporting. Where only the month is known, events are shown on
                    the first of that month.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Milestones list / calendar ──────────────────────────────────────── --}}
    {{-- $upcoming and $calEvents are passed from EventController@index --}}

    <section
        class="section"
        x-data="{
            view: 'list',
            currentYear: 2026,
            currentMonth: 5,
            calEvents: {{ Js::from($calEvents) }},

            get monthLabel() {
                return new Date(this.currentYear, this.currentMonth, 1)
                    .toLocaleString('en-GB', { month: 'long', year: 'numeric' });
            },

            get calDays() {
                const first   = new Date(this.currentYear, this.currentMonth, 1);
                const last    = new Date(this.currentYear, this.currentMonth + 1, 0);
                const today   = new Date();
                const pad     = first.getDay();
                const days    = [];

                for (let i = pad; i > 0; i--) {
                    const d = new Date(this.currentYear, this.currentMonth, 1 - i);
                    days.push({ day: d.getDate(), cur: false, today: false, events: [] });
                }
                for (let d = 1; d <= last.getDate(); d++) {
                    const isToday = today.getFullYear() === this.currentYear
                                 && today.getMonth()    === this.currentMonth
                                 && today.getDate()     === d;
                    const evs = this.calEvents.filter(e =>
                        e.jsMonth === this.currentMonth && e.day === d && e.year === this.currentYear
                    );
                    days.push({ day: d, cur: true, today: isToday, events: evs });
                }
                let t = 1;
                while (days.length < 42) days.push({ day: t++, cur: false, today: false, events: [] });
                return days;
            },

            prevMonth() {
                if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
                else this.currentMonth--;
            },
            nextMonth() {
                if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
                else this.currentMonth++;
            },
            goToEvent(idx) {
                this.view = 'list';
                this.$nextTick(() => {
                    const el = document.getElementById('event-' + idx);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        }"
    >
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">The Record</span>
                <h2 class="section-header__title">Milestones</h2>
                <p class="section-header__lead">
                    Browse as a list, or switch to the calendar to see when each
                    milestone landed.
                </p>
            </div>

            {{-- View toggle --}}
            <div style="display:flex; justify-content:center;" data-reveal data-reveal-delay="80">
                <div class="view-toggle">
                    <button
                        class="view-toggle__btn"
                        :class="{ 'is-active': view === 'list' }"
                        @click="view = 'list'"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                        List View
                    </button>
                    <button
                        class="view-toggle__btn"
                        :class="{ 'is-active': view === 'calendar' }"
                        @click="view = 'calendar'"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Calendar View
                    </button>
                </div>
            </div>

            {{-- ── List view ──────────────────────────────────────────────────── --}}
            <div class="events-full" x-show="view === 'list'" x-transition>
                @forelse($upcoming as $i => $event)
                <div class="event-card" id="event-{{ $i }}" data-reveal data-reveal-delay="{{ $i * 80 }}">

                    <div class="event-card__date">
                        <div class="event-card__date-day">{{ $event->date_day }}</div>
                        <div class="event-card__date-month">{{ $event->date_month }}</div>
                        <div class="event-card__date-year">{{ $event->date_year }}</div>
                    </div>

                    <div class="event-card__body">
                        <span class="event-card__badge event-card__badge--{{ $event->badge }}">
                            {{ $event->badgeLabel() }}
                        </span>
                        <h3 class="event-card__title">
                            <a href="{{ route('events.show', $event) }}" style="color:inherit; text-decoration:none;">
                                {{ $event->title }}
                            </a>
                        </h3>
                        <div class="event-card__meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $event->location }}
                        </div>
                        <p class="event-card__desc">{{ $event->description }}</p>
                    </div>

                    <div class="event-card__actions">
                        <a href="{{ route('events.show', $event) }}" class="btn btn--outline-navy btn--sm">Details</a>

                        {{-- Add to Calendar dropdown --}}
                        <div class="cal-drop" x-data="{ open: false }" @click.outside="open = false">
                            <button
                                class="btn btn--outline-navy btn--sm"
                                @click="open = !open"
                                :aria-expanded="open"
                                style="display:flex; align-items:center; gap:6px;"
                            >
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                + Calendar
                            </button>

                            <div class="cal-drop__menu" x-show="open" x-transition x-cloak>
                                <div class="cal-drop__label">Add to Calendar</div>
                                <a href="{{ $event->gcalUrl() }}" target="_blank" rel="noopener" class="cal-drop__item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Google Calendar
                                </a>
                                <a href="{{ $event->icsUrl() }}" class="cal-drop__item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 6v6l4 2"/></svg>
                                    Apple Calendar (.ics)
                                </a>
                                <a href="{{ $event->outlookUrl() }}" target="_blank" rel="noopener" class="cal-drop__item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                                    Outlook Calendar
                                </a>
                                <a href="{{ $event->icsUrl() }}" download class="cal-drop__item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download .ics
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                @empty
                <x-empty-state message="Upcoming events will be listed here soon. Check back shortly." />
                @endforelse
            </div>

            {{-- ── Calendar view ──────────────────────────────────────────────── --}}
            <div x-show="view === 'calendar'" x-transition x-cloak>
                <div class="cal-wrap">

                    {{-- Month nav --}}
                    <div class="cal-header">
                        <div class="cal-header__title" x-text="monthLabel"></div>
                        <div class="cal-header__controls">
                            <button class="cal-header__nav" @click="prevMonth()" aria-label="Previous month">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"/>
                                </svg>
                            </button>
                            <button class="cal-header__nav" @click="nextMonth()" aria-label="Next month">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Day-of-week headers --}}
                    <div class="cal-grid">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $wd)
                        <div class="cal-weekday">{{ $wd }}</div>
                        @endforeach

                        {{-- Day cells --}}
                        <template x-for="(cell, idx) in calDays" :key="idx">
                            <div
                                class="cal-day"
                                :class="{
                                    'cal-day--other': !cell.cur,
                                    'cal-day--today': cell.today
                                }"
                            >
                                <div class="cal-day__num" x-text="cell.day"></div>
                                <template x-for="(ev, ei) in cell.events" :key="ei">
                                    <span
                                        class="cal-pill"
                                        x-text="ev.title"
                                        :title="ev.title"
                                        @click="goToEvent(calEvents.indexOf(ev))"
                                    ></span>
                                </template>
                            </div>
                        </template>
                    </div>

                </div>

                {{-- Legend --}}
                <div style="display:flex; align-items:center; gap:8px; margin-top:16px; font-size:0.78rem; color:var(--muted);">
                    <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#c9a227;"></span>
                    Milestone on this date — click to jump to details
                </div>
            </div>

        </div>
    </section>

    {{-- ── Earlier milestones ───────────────────────────────────────────────── --}}
    <section class="section section--grey">
        <div class="container">

            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Earlier</span>
                <h2 class="section-header__title">2018 – 2023</h2>
                <p class="section-header__lead">
                    The elections, launches and groundworks that set the term in motion.
                </p>
            </div>

            @php
            $past = [
                ['May 2018', 'Elected Lord Mayor of Kanifing',            'Kanifing Municipal Council'],
                ['2019',     'Mbalit Project Launched',                   'Kanifing Municipality'],
                ['2021',     'Kanifing Environmental Transformation Programme Begins', 'KMC & Peterborough City Council'],
                ['Jul 2021', 'WasteAid Composting Pilot Launched',        'Bakau & Abuko'],
                ['Aug 2022', 'Municipal Library Foundation Stone Laid',   'Kanifing Municipality'],
                ['Dec 2022', 'End-of-Term Awards Night',                  'Kanifing Municipal Council'],
                ['May 2023', 'Re-elected Lord Mayor of Kanifing',         'Kanifing Municipal Council'],
            ];
            @endphp

            <div class="past-events-grid">
                @foreach($past as $i => [$date, $title, $loc])
                <div class="past-event" data-reveal data-reveal-delay="{{ $i * 60 }}">
                    <div class="past-event__date">{{ $date }}</div>
                    <div class="past-event__title">{{ $title }}</div>
                    <div class="past-event__location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $loc }}
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>Stay in the Loop</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                New milestones and public events are added as they happen.
                Get in touch and we'll keep you posted.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/contact') }}" class="btn btn--navy btn--lg">Get in Touch</a>
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--outline-navy btn--lg">See the Record</a>
            </div>
        </div>
    </section>

</x-app-layout>
