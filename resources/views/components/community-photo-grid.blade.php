@props([
    'photos',
    'emptyMessage' => 'Photos are coming soon.',
])

@php
    $items = $photos->values()->map(fn ($photo) => [
        'caption' => $photo->caption,
        'tag'     => $photo->tag,
        'file'    => $photo->imageUrl(),
    ])->all();
@endphp

@if (empty($items))
    <x-empty-state :message="$emptyMessage" />
@else
    <div
        x-data="{
            lightboxOpen: false,
            current: 0,
            photos: {{ Js::from($items) }},

            open(idx) { this.current = idx; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
            close() { this.lightboxOpen = false; document.body.style.overflow = ''; },
            prev() { this.current = (this.current - 1 + this.photos.length) % this.photos.length; },
            next() { this.current = (this.current + 1) % this.photos.length; },
        }"
        @keydown.escape.window="if (lightboxOpen) close()"
        @keydown.arrow-left.window="if (lightboxOpen) prev()"
        @keydown.arrow-right.window="if (lightboxOpen) next()"
    >
        <div class="community-grid">
            @foreach ($items as $i => $photo)
            <div
                class="community-photo"
                data-reveal
                data-reveal-delay="{{ $i * 80 }}"
                @click="open({{ $i }})"
                role="button"
                aria-label="Open photo: {{ $photo['caption'] }}"
                tabindex="0"
                @keydown.enter="open({{ $i }})"
                @keydown.space.prevent="open({{ $i }})"
            >
                @if ($photo['file'])
                    <img src="{{ $photo['file'] }}" alt="{{ $photo['caption'] }}" loading="lazy">
                @else
                    <div class="community-photo__placeholder">
                        <svg class="community-photo__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                @endif

                <div class="community-photo__zoom">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                        <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                    </svg>
                </div>

                <div class="community-photo__overlay">
                    @if ($photo['tag'])
                        <span class="community-photo__tag">{{ $photo['tag'] }}</span>
                    @endif
                    <p class="community-photo__caption">{{ $photo['caption'] }}</p>
                </div>
            </div>
            @endforeach
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
            :aria-label="photos[current] ? photos[current].caption : 'Photo lightbox'"
        >
            <div class="lightbox__counter" x-text="`${current + 1} / ${photos.length}`"></div>

            <button class="lightbox__close" @click="close()" aria-label="Close lightbox">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <button class="lightbox__nav lightbox__nav--prev" @click="prev()" aria-label="Previous photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            <div class="lightbox__stage">
                <div class="lightbox__media">
                    <img x-show="photos[current] && photos[current].file"
                         :src="photos[current] ? photos[current].file : ''"
                         :alt="photos[current] ? photos[current].caption : ''">
                    <div class="lightbox__placeholder" x-show="!photos[current] || !photos[current].file">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                </div>

                <div class="lightbox__caption-wrap" x-show="photos[current]">
                    <div class="lightbox__tag" x-text="photos[current] ? photos[current].tag : ''"></div>
                    <p class="lightbox__caption" x-text="photos[current] ? photos[current].caption : ''"></p>
                </div>
            </div>

            <button class="lightbox__nav lightbox__nav--next" @click="next()" aria-label="Next photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </div>
@endif
