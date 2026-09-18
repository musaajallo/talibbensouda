<x-app-layout title="Gallery" styles="gallery" description="Photos from Talib Ahmed Bensouda's work and campaign across the Kanifing Municipality.">

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
    // Managed in the admin panel (Content → Gallery). Each row carries a
    // `file` URL when a photo has been uploaded; otherwise the grid falls
    // back to a coloured placeholder tile.
    $palette = ['#0d1b38', '#0a1931', '#0f2040', '#112044', '#091422'];

    $photos = \App\Models\GalleryPhoto::published()
        ->with('media')
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get()
        ->values()
        ->map(fn ($photo, $i) => [
            'category' => $photo->category,
            'caption'  => $photo->caption,
            'wide'     => (bool) $photo->wide,
            'isVideo'  => $photo->isVideo(),
            'file'     => $photo->thumbnailUrl(),
            'embedUrl' => $photo->youtubeEmbedUrl(),
            'bg'       => $palette[$i % count($palette)],
        ])
        ->all();

    // Managed in the admin panel (Page content → Gallery page).
    $perPage = app(\App\Settings\GalleryPageSettings::class)->photos_per_page;
    @endphp

    <section
        class="section"
        x-data="{
            categories: ['All', 'Projects', 'Community', 'Events', 'Partners'],
            mediaTypes: ['All', 'Photos', 'Videos'],
            active: 'All',
            activeMedia: 'All',
            lightboxOpen: false,
            current: 0,
            page: 1,
            perPage: {{ Js::from($perPage) }},
            photos: {{ Js::from($photos) }},

            get filtered() {
                return this.photos
                    .map((p, i) => ({ ...p, i }))
                    .filter(p => this.active === 'All' || p.category === this.active)
                    .filter(p => this.activeMedia === 'All' || (this.activeMedia === 'Videos') === p.isVideo);
            },

            // Each item also carries its position within `filtered` (`fi`) so a
            // click on a paginated tile still opens the lightbox at the right
            // spot, and prev/next can keep cycling across the whole filtered
            // set — not just the current page.
            get paged() {
                const start = (this.page - 1) * this.perPage;
                return this.filtered.map((p, fi) => ({ ...p, fi })).slice(start, start + this.perPage);
            },

            get totalPages() {
                return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
            },

            setCategory(cat) { this.active = cat; this.page = 1; },
            setMediaType(type) { this.activeMedia = type; this.page = 1; },
            goToPage(p) { this.page = p; this.$refs.galleryTop.scrollIntoView({ behavior: 'smooth', block: 'start' }); },

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

            {{-- Filter tabs — category on the left, media type on the right --}}
            <div class="gallery-filter-row" data-reveal x-ref="galleryTop">
                <div class="gallery-filter">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            class="filter-tab"
                            :class="{ 'is-active': active === cat }"
                            :aria-pressed="(active === cat).toString()"
                            @click="setCategory(cat)"
                            x-text="cat"
                        ></button>
                    </template>
                </div>

                {{-- Independent of category, combines with it — a different
                     (complementary blue) active colour on purpose, so the two
                     pill groups read as separate controls at a glance. --}}
                <div class="gallery-filter gallery-filter--media">
                    <template x-for="type in mediaTypes" :key="type">
                        <button
                            class="filter-tab filter-tab--media"
                            :class="{ 'is-active': activeMedia === type }"
                            :aria-pressed="(activeMedia === type).toString()"
                            @click="setMediaType(type)"
                            x-text="type"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Photo count --}}
            <p class="gallery-count" data-reveal data-reveal-delay="80">
                Showing <span x-text="filtered.length"></span>
                <span x-text="activeMedia === 'Videos' ? 'videos' : (activeMedia === 'Photos' ? 'photos' : 'items')"></span>
                <span x-show="active !== 'All'" x-cloak> in <span x-text="active"></span></span>
            </p>

            {{-- Grid --}}
            <div class="gallery-grid">
                <template x-for="photo in paged" :key="photo.i">
                    <div
                        class="gallery-item"
                        @click="open(photo.fi)"
                        :style="`background-color: ${photo.bg}`"
                        role="button"
                        :aria-label="`${photo.isVideo ? 'Play video' : 'Open photo'}: ${photo.caption}`"
                        tabindex="0"
                        @keydown.enter="open(photo.fi)"
                        @keydown.space.prevent="open(photo.fi)"
                    >
                        <img x-show="photo.file" :src="photo.file" :alt="photo.caption" loading="lazy">
                        <div class="gallery-item__placeholder" x-show="!photo.file" :style="{ backgroundColor: photo.bg }">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>

                        <div class="gallery-item__zoom" x-show="!photo.isVideo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                                <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                            </svg>
                        </div>

                        <div class="gallery-item__play" x-show="photo.isVideo" x-cloak>
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </div>

                        <div class="gallery-item__overlay">
                            <span class="gallery-item__tag" x-text="photo.category"></span>
                            <p class="gallery-item__caption" x-text="photo.caption"></p>
                        </div>
                    </div>
                </template>
            </div>

            <template x-if="filtered.length === 0">
                <x-empty-state message="Photos will be added to the gallery soon." />
            </template>

            {{-- Pagination --}}
            <nav class="gallery-pagination" x-show="totalPages > 1" x-cloak aria-label="Gallery pages">
                <button
                    class="gallery-pagination__nav"
                    @click="goToPage(page - 1)"
                    :disabled="page === 1"
                    aria-label="Previous page"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>

                <template x-for="p in totalPages" :key="p">
                    <button
                        class="gallery-pagination__num"
                        :class="{ 'is-active': p === page }"
                        :aria-current="p === page ? 'page' : null"
                        @click="goToPage(p)"
                        x-text="p"
                    ></button>
                </template>

                <button
                    class="gallery-pagination__nav"
                    @click="goToPage(page + 1)"
                    :disabled="page === totalPages"
                    aria-label="Next page"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </nav>

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
            x-trap="lightboxOpen"
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
                    {{-- Video: an x-if (not x-show) so the iframe node is fully
                         torn down on a non-video slide or on close, rather than
                         just hidden — otherwise the video keeps playing,
                         invisibly, in the background. --}}
                    <template x-if="filtered[current] && filtered[current].isVideo">
                        <iframe
                            class="lightbox__video"
                            :src="lightboxOpen ? filtered[current].embedUrl : ''"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                    </template>
                    <img x-show="filtered[current] && filtered[current].file && !filtered[current].isVideo"
                         :src="filtered[current] ? filtered[current].file : ''"
                         :alt="filtered[current] ? filtered[current].caption : ''">
                    <div class="lightbox__placeholder"
                         x-show="!filtered[current] || (!filtered[current].file && !filtered[current].isVideo)"
                         :style="{ backgroundColor: filtered[current] ? filtered[current].bg : '' }">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
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
