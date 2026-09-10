<x-app-layout :title="$event->title">

    {{-- ── Page hero ──────────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">

                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('events.index') }}">Events</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">{{ $event->title }}</span>
                </nav>

                @if($event->flag)
                    <div style="font-size:2rem; line-height:1; margin-bottom:16px;">{{ $event->flag }}</div>
                @endif

                <span class="page-hero__eyebrow">{{ $event->badgeLabel() }}</span>

                <h1 class="page-hero__title">{{ $event->title }}</h1>

                <p class="page-hero__subtitle">
                    {{ $event->date_day }} {{ $event->date_month }} {{ $event->date_year }}
                    &nbsp;·&nbsp;
                    {{ $event->location }}
                </p>

            </div>
        </div>
    </section>

    {{-- ── Event detail ────────────────────────────────────────────────────────── --}}
    <section class="event-show">
        <div class="container">
            <div class="event-show__layout">

                {{-- ── Main content ── --}}
                <div class="event-show__content" data-reveal>

                    {{-- Meta strip --}}
                    <div class="event-show__meta">
                        <div class="event-show__meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <div>
                                <div class="event-show__meta-label">Date</div>
                                <div class="event-show__meta-value">{{ $event->date_day }} {{ $event->date_month }} {{ $event->date_year }}</div>
                            </div>
                        </div>

                        <div class="event-show__meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <div>
                                <div class="event-show__meta-label">Location</div>
                                <div class="event-show__meta-value">{{ $event->location }}</div>
                            </div>
                        </div>

                        @if($event->venue)
                        <div class="event-show__meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            <div>
                                <div class="event-show__meta-label">Venue</div>
                                <div class="event-show__meta-value">{{ $event->venue }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Full description --}}
                    <div class="event-show__body">
                        @foreach(explode("\n\n", $event->full_description ?? $event->description) as $para)
                            @if(trim($para))
                                <p>{{ trim($para) }}</p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Back link --}}
                    <div style="margin-top: 48px;">
                        <a href="{{ route('events.index') }}" class="btn btn--outline-navy" style="display:inline-flex; align-items:center; gap:8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                            </svg>
                            All Events
                        </a>
                    </div>

                </div>

                {{-- ── Sidebar ── --}}
                <aside class="event-show__sidebar" data-reveal data-reveal-delay="80">

                    <div class="event-info-card">

                        {{-- Date header --}}
                        <div class="event-info-card__header">
                            <div class="event-info-card__date-day">{{ $event->date_day }}</div>
                            <div class="event-info-card__date-label">{{ $event->date_month }} {{ $event->date_year }}</div>
                        </div>

                        {{-- Detail rows --}}
                        <div class="event-info-card__body">

                            <div class="event-info-card__row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                                <div>
                                    <div class="event-info-card__row-label">Location</div>
                                    <div class="event-info-card__row-value">{{ $event->location }}</div>
                                </div>
                            </div>

                            @if($event->venue)
                            <div class="event-info-card__row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                                <div>
                                    <div class="event-info-card__row-label">Venue</div>
                                    <div class="event-info-card__row-value">{{ $event->venue }}</div>
                                </div>
                            </div>
                            @endif

                        </div>

                        {{-- CTA buttons --}}
                        <div class="event-info-card__actions">
                            <a href="{{ route('events.register') }}" class="btn btn--gold" style="text-align:center;">
                                Register Your Interest
                            </a>
                        </div>

                        {{-- Add to Calendar --}}
                        <div class="event-info-card__cal-section">
                            <div class="event-info-card__cal-label">Add to Calendar</div>
                            <div class="event-info-card__cal-links">

                                <a href="{{ $event->gcalUrl() }}" target="_blank" rel="noopener" class="event-info-card__cal-link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    Google Calendar
                                </a>

                                <a href="{{ $event->icsUrl() }}" class="event-info-card__cal-link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 6v6l4 2"/>
                                    </svg>
                                    Apple Calendar (.ics)
                                </a>

                                <a href="{{ $event->outlookUrl() }}" target="_blank" rel="noopener" class="event-info-card__cal-link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                                    </svg>
                                    Outlook Calendar
                                </a>

                                <a href="{{ $event->icsUrl() }}" download class="event-info-card__cal-link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download .ics
                                </a>

                            </div>
                        </div>

                    </div>

                </aside>

            </div>

            {{-- ── Related Events ──────────────────────────────────────────── --}}
            @if($related->isNotEmpty())
            <div class="related-events" data-reveal>

                <div class="related-events__header">
                    <h2 class="related-events__title">More Milestones</h2>
                    <a href="{{ route('events.index') }}" class="btn btn--outline-navy btn--sm">View All</a>
                </div>

                <div class="related-events__grid">
                    @foreach($related as $rel)
                    <a href="{{ route('events.show', $rel) }}" class="related-event-card">
                        <div class="related-event-card__date">
                            {{ $rel->date_day }} {{ $rel->date_month }} {{ $rel->date_year }}
                        </div>
                        <div class="related-event-card__title">{{ $rel->title }}</div>
                        <div class="related-event-card__loc">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $rel->location }}
                        </div>
                    </a>
                    @endforeach
                </div>

            </div>
            @endif

        </div>
    </section>

</x-app-layout>
