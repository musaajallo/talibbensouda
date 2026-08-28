<x-app-layout>
@php $title = 'Gallery' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Gallery</span>
                </nav>
                <span class="page-hero__eyebrow">Photo Gallery</span>
                <h1 class="page-hero__title">Moments That Matter</h1>
                <p class="page-hero__subtitle">
                    A visual record of Talib Bensouda's work — in communities,
                    at events, and with Gambians around the world.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Gallery ──────────────────────────────────────────────────────────── --}}
    @php
    // Placeholder set — swap captions and add real image files as photos are chosen.
    $photos = [
        // Projects
        ['category' => 'Projects',  'caption' => 'Mbalit Project — new compactor fleet',        'wide' => true,  'bg' => '#0d1b38'],
        ['category' => 'Projects',  'caption' => 'Kanifing Municipal Library & Innovation Hub', 'wide' => false, 'bg' => '#0a1931'],
        ['category' => 'Projects',  'caption' => 'Road Network Project — Latrikunda',           'wide' => false, 'bg' => '#0f2040'],
        ['category' => 'Projects',  'caption' => 'Serrekunda Market upgrade',                   'wide' => false, 'bg' => '#112044'],
        ['category' => 'Projects',  'caption' => 'Bakoteh dumpsite fencing & remediation',      'wide' => false, 'bg' => '#0d1b38'],
        ['category' => 'Projects',  'caption' => 'Bundung Maternity Ward expansion',            'wide' => false, 'bg' => '#0a1931'],

        // Community
        ['category' => 'Community', 'caption' => 'Ward Development Committee office',            'wide' => true,  'bg' => '#112044'],
        ['category' => 'Community', 'caption' => "Bakau Women's Garden",                        'wide' => false, 'bg' => '#0d1b38'],
        ['category' => 'Community', 'caption' => "Mayor's Trophy football tournament",          'wide' => false, 'bg' => '#0a1931'],
        ['category' => 'Community', 'caption' => 'Set settal community clean-up',               'wide' => false, 'bg' => '#0f2040'],
        ['category' => 'Community', 'caption' => 'Sewing machines for community centres',       'wide' => false, 'bg' => '#112044'],

        // Events
        ['category' => 'Events',    'caption' => 'Municipal Library inauguration, 2024',        'wide' => true,  'bg' => '#091422'],
        ['category' => 'Events',    'caption' => 'UN Deputy Secretary-General visit, 2025',     'wide' => false, 'bg' => '#0d1b38'],
        ['category' => 'Events',    'caption' => 'Bakau Multipurpose Facility launch, 2025',    'wide' => false, 'bg' => '#0a1931'],
        ['category' => 'Events',    'caption' => 'End-of-term awards night, 2022',              'wide' => false, 'bg' => '#0f2040'],

        // Partners
        ['category' => 'Partners',  'caption' => 'Peterborough City Council — KETP partnership','wide' => true,  'bg' => '#0d1b38'],
        ['category' => 'Partners',  'caption' => 'Sister-city ties — Freetown & Madison',       'wide' => false, 'bg' => '#0a1931'],
        ['category' => 'Partners',  'caption' => 'Global Parliament of Mayors',                 'wide' => false, 'bg' => '#112044'],
    ];
    @endphp

    <section
        class="section"
        x-data="{
            categories: ['All', 'Projects', 'Community', 'Events', 'Partners'],
            active: 'All',
            lightboxOpen: false,
            current: 0,
            photos: {{ Js::from($photos) }},

            get filtered() {
                if (this.active === 'All') return this.photos.map((p, i) => ({ ...p, i }));
                return this.photos.map((p, i) => ({ ...p, i })).filter(p => p.category === this.active);
            },

            open(idx) { this.current = idx; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
            close() { this.lightboxOpen = false; document.body.style.overflow = ''; },
            prev() { this.current = (this.current - 1 + this.filtered.length) % this.filtered.length; },
            next() { this.current = (this.current + 1) % this.filtered.length; },
        }"
        @keydown.escape.window="if (lightboxOpen) close()"
        @keydown.arrow-left.window="if (lightboxOpen) prev()"
        @keydown.arrow-right.window="if (lightboxOpen) next()"
    >
        <div class="container">

            {{-- Filter tabs --}}
            <div class="gallery-filter" data-reveal>
                <template x-for="cat in categories" :key="cat">
                    <button
                        class="filter-tab"
                        :class="{ 'is-active': active === cat }"
                        @click="active = cat"
                        x-text="cat"
                    ></button>
                </template>
            </div>

            {{-- Photo count --}}
            <p class="gallery-count" data-reveal data-reveal-delay="80">
                Showing <span x-text="filtered.length"></span> photos
                <span x-show="active !== 'All'" x-cloak> in <span x-text="active"></span></span>
            </p>

            {{-- Grid --}}
            <div class="gallery-grid">
                <template x-for="(photo, idx) in filtered" :key="photo.i">
                    <div
                        class="gallery-item"
                        :class="{ 'gallery-item--wide': photo.wide }"
                        @click="open(idx)"
                        :style="`background-color: ${photo.bg}`"
                        role="button"
                        :aria-label="`Open photo: ${photo.caption}`"
                        tabindex="0"
                        @keydown.enter="open(idx)"
                    >
                        {{-- Placeholder (remove when adding real images) --}}
                        <div class="gallery-item__placeholder" :style="`background-color: ${photo.bg}`">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        {{--
                            Replace placeholder div above with:
                            <img :src="`/images/gallery/${photo.file}`" :alt="photo.caption">
                        --}}

                        <div class="gallery-item__zoom">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                                <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                            </svg>
                        </div>

                        <div class="gallery-item__overlay">
                            <span class="gallery-item__tag" x-text="photo.category"></span>
                            <p class="gallery-item__caption" x-text="photo.caption"></p>
                        </div>
                    </div>
                </template>
            </div>

        </div>

        {{-- ── Lightbox ──────────────────────────────────────────────────────── --}}
        <div
            class="lightbox"
            x-show="lightboxOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="close()"
            x-cloak
            role="dialog"
            aria-modal="true"
            :aria-label="filtered[current] ? filtered[current].caption : 'Photo lightbox'"
        >
            {{-- Counter --}}
            <div class="lightbox__counter" x-text="`${current + 1} / ${filtered.length}`"></div>

            {{-- Close --}}
            <button class="lightbox__close" @click="close()" aria-label="Close lightbox">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            {{-- Prev --}}
            <button class="lightbox__nav lightbox__nav--prev" @click="prev()" aria-label="Previous photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            {{-- Stage --}}
            <div class="lightbox__stage">
                <div class="lightbox__media" x-show="filtered.length > 0">
                    <div class="lightbox__placeholder" :style="filtered[current] ? `background-color: ${filtered[current].bg}` : ''">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    {{--
                        When real images exist, replace the placeholder div with:
                        <img :src="filtered[current] ? `/images/gallery/${filtered[current].file}` : ''"
                             :alt="filtered[current] ? filtered[current].caption : ''">
                    --}}
                </div>

                <div class="lightbox__caption-wrap" x-show="filtered[current]">
                    <div class="lightbox__tag" x-text="filtered[current] ? filtered[current].category : ''"></div>
                    <p class="lightbox__caption" x-text="filtered[current] ? filtered[current].caption : ''"></p>
                </div>
            </div>

            {{-- Next --}}
            <button class="lightbox__nav lightbox__nav--next" @click="next()" aria-label="Next photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>

        </div>

    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
    <section class="cta-banner">
        <div class="container">
            <h2 class="cta-banner__title" data-reveal>See the Work Behind the Photos</h2>
            <p class="cta-banner__lead" data-reveal data-reveal-delay="100">
                Every image here points to a project, a partnership or a milestone
                in Kanifing. Explore the full record.
            </p>
            <div class="cta-banner__actions" data-reveal data-reveal-delay="200">
                <a href="{{ url('/peoples-mayor') }}" class="btn btn--navy btn--lg">The People's Mayor</a>
                <a href="{{ url('/events') }}" class="btn btn--outline-navy btn--lg">Events &amp; Milestones</a>
            </div>
        </div>
    </section>

</x-app-layout>
