@props([
    'videos',
    'ctaLabel' => '',
    'ctaUrl' => '',
])

{{-- Horizontal, swipeable strip of YouTube videos (GalleryPhoto rows with
     type = video). Clicking a slide opens it in the shared `.lightbox`.
     Renders nothing when there are no videos.

     data-reveal goes on .video-slider only, never on this wrapper: a
     transformed ancestor would turn the lightbox's `position: fixed` into
     "fixed relative to that ancestor". --}}
@php
    $items = $videos->values()->map(fn ($video) => [
        'caption'  => $video->caption ?: 'Video',
        'category' => $video->category,
        'thumb'    => $video->thumbnailUrl(),
        'embedUrl' => $video->youtubeEmbedUrl(),
    ])->all();
@endphp

@if (! empty($items))
    <div
        x-data="{
            videos: {{ Js::from($items) }},
            lightboxOpen: false,
            current: 0,
            atStart: true,
            atEnd: false,

            init() {
                this.$nextTick(() => this.update());
                window.addEventListener('resize', () => this.update());
            },
            update() {
                const t = this.$refs.track;
                this.atStart = t.scrollLeft <= 4;
                this.atEnd = t.scrollLeft + t.clientWidth >= t.scrollWidth - 4;
            },
            slide(dir) {
                const t = this.$refs.track;
                const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                t.scrollBy({ left: dir * t.clientWidth * 0.85, behavior: calm ? 'auto' : 'smooth' });
            },

            open(idx) { this.current = idx; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
            close() { this.lightboxOpen = false; document.body.style.overflow = ''; },
            prev() { this.current = (this.current - 1 + this.videos.length) % this.videos.length; },
            next() { this.current = (this.current + 1) % this.videos.length; },
        }"
        @keydown.escape.window="if (lightboxOpen) close()"
        @keydown.arrow-left.window="if (lightboxOpen) prev()"
        @keydown.arrow-right.window="if (lightboxOpen) next()"
    >
        <div class="video-slider" data-reveal>
            <div class="video-slider__track" x-ref="track" @scroll.passive="update()">
                @foreach ($items as $i => $video)
                    <button
                        type="button"
                        class="video-slide"
                        @click="open({{ $i }})"
                        aria-label="Play video: {{ $video['caption'] }}"
                    >
                        <span class="video-slide__thumb">
                            @if ($video['thumb'])
                                <img src="{{ $video['thumb'] }}" alt="" loading="lazy" width="480" height="360">
                            @endif
                            <span class="video-slide__play" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                        </span>
                        <span class="video-slide__meta">
                            <span class="video-slide__tag">{{ $video['category'] }}</span>
                            <span class="video-slide__title">{{ $video['caption'] }}</span>
                        </span>
                    </button>
                @endforeach
            </div>

            <div class="video-slider__controls">
                <div class="video-slider__arrows">
                    <button type="button" class="video-slider__arrow" @click="slide(-1)" :disabled="atStart" aria-label="Previous videos">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button type="button" class="video-slider__arrow" @click="slide(1)" :disabled="atEnd" aria-label="Next videos">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                @if ($ctaLabel && $ctaUrl)
                    <a href="{{ $ctaUrl }}" class="btn btn--gold">{{ $ctaLabel }}</a>
                @endif
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
            x-trap="lightboxOpen"
            role="dialog"
            aria-modal="true"
            :aria-label="videos[current] ? videos[current].caption : 'Video'"
        >
            <div class="lightbox__counter" x-show="videos.length > 1" x-text="`${current + 1} / ${videos.length}`"></div>

            <button class="lightbox__close" @click="close()" aria-label="Close video">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <button class="lightbox__nav lightbox__nav--prev" x-show="videos.length > 1" @click="prev()" aria-label="Previous video">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <div class="lightbox__stage">
                <div class="lightbox__media">
                    {{-- x-if, not x-show: the iframe is torn down on close so the
                         video stops, instead of playing on invisibly. --}}
                    <template x-if="lightboxOpen && videos[current]">
                        <iframe
                            class="lightbox__video"
                            :src="videos[current].embedUrl"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                    </template>
                </div>

                <div class="lightbox__caption-wrap" x-show="videos[current]">
                    <div class="lightbox__tag" x-text="videos[current] ? videos[current].category : ''"></div>
                    <p class="lightbox__caption" x-text="videos[current] ? videos[current].caption : ''"></p>
                </div>
            </div>

            <button class="lightbox__nav lightbox__nav--next" x-show="videos.length > 1" @click="next()" aria-label="Next video">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
@endif
