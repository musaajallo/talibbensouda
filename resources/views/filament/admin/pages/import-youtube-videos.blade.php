<x-filament-panels::page>
    @if (! $this->isConfigured())
        <x-filament::section>
            <x-filament::empty-state
                icon="heroicon-o-key"
                heading="No YouTube API key configured"
                description="Add YOUTUBE_API_KEY to the server's .env (a free key from console.cloud.google.com — enable “YouTube Data API v3”, then Credentials → Create API key) to browse the channel's videos here."
            />
        </x-filament::section>
    @else
        @php $split = $this->splitAvailableAndImported(); @endphp

        <div class="flex flex-wrap items-center gap-3">
            <div class="fi-input-wrp flex-1 min-w-[220px] max-w-md rounded-lg shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20">
                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search this channel's videos by title…"
                    class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-sm text-gray-950 outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-500"
                />
            </div>

            <button
                type="button"
                wire:click="loadVideos"
                wire:loading.attr="disabled"
                wire:target="loadVideos"
                class="fi-btn fi-btn-size-sm relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg inline-grid gap-1.5 px-3 py-2 text-sm ring-1 ring-gray-950/10 hover:bg-gray-50 dark:ring-white/20 dark:hover:bg-white/5 dark:text-white"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                </svg>
                Refresh from YouTube
            </button>

            <x-filament::loading-indicator wire:loading wire:target="search, loadVideos, loadMore" class="h-5 w-5 text-gray-400" />
        </div>

        @if (empty($this->videos))
            <div class="mt-6">
                <x-filament::empty-state
                    icon="heroicon-o-video-camera"
                    heading="No videos found"
                    :description="filled($search) ? 'No titles on the channel match that search.' : 'Nothing came back from the channel — check the handle in config/services.php.'"
                />
            </div>
        @else
            {{-- ── Available to add ────────────────────────────────────────── --}}
            <div class="mt-8">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    Available to add
                    <span class="ml-1 text-sm font-normal text-gray-500 dark:text-gray-400">({{ count($split['available']) }})</span>
                </h2>

                @if (empty($split['available']))
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Everything found here is already in the Gallery.</p>
                @else
                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($split['available'] as $video)
                            <div class="youtube-video-card">
                                <button
                                    type="button"
                                    wire:click="mountAction('preview', { videoId: '{{ $video['id'] }}', title: @js($video['title']) })"
                                    class="youtube-video-card__thumb"
                                    aria-label="Preview: {{ $video['title'] }}"
                                >
                                    @if ($video['thumbnail'])
                                        <img src="{{ $video['thumbnail'] }}" alt="" loading="lazy">
                                    @endif
                                    <span class="youtube-video-card__play">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </button>

                                <div class="youtube-video-card__body">
                                    <p class="youtube-video-card__title">{{ $video['title'] }}</p>
                                    @if ($video['published_at'])
                                        <p class="youtube-video-card__date">{{ \Illuminate\Support\Carbon::parse($video['published_at'])->format('j M Y') }}</p>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="mountAction('addToGallery', { videoId: '{{ $video['id'] }}', title: @js($video['title']) })"
                                        class="fi-btn fi-btn-size-sm relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg fi-color-primary fi-btn-color-primary inline-grid gap-1.5 fi-ac-btn-action px-3 py-2 text-sm bg-custom-600 text-white hover:bg-custom-500 mt-3 w-full"
                                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                                    >
                                        Add to Gallery
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Already in the Gallery ──────────────────────────────────── --}}
            @if (! empty($split['imported']))
                <div class="mt-10">
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                        Already in the Gallery
                        <span class="ml-1 text-sm font-normal text-gray-500 dark:text-gray-400">({{ count($split['imported']) }})</span>
                    </h2>

                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($split['imported'] as $video)
                            <div class="youtube-video-card youtube-video-card--imported">
                                <button
                                    type="button"
                                    wire:click="mountAction('preview', { videoId: '{{ $video['id'] }}', title: @js($video['title']) })"
                                    class="youtube-video-card__thumb"
                                    aria-label="Preview: {{ $video['title'] }}"
                                >
                                    @if ($video['thumbnail'])
                                        <img src="{{ $video['thumbnail'] }}" alt="" loading="lazy">
                                    @endif
                                    <span class="youtube-video-card__play">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </button>

                                <div class="youtube-video-card__body">
                                    <p class="youtube-video-card__title">{{ $video['title'] }}</p>
                                    @if ($video['published_at'])
                                        <p class="youtube-video-card__date">{{ \Illuminate\Support\Carbon::parse($video['published_at'])->format('j M Y') }}</p>
                                    @endif

                                    <div class="mt-3 flex items-center gap-2">
                                        <x-filament::badge color="success" icon="heroicon-o-check-circle">In the Gallery</x-filament::badge>
                                        <a href="{{ static::getGalleryEditUrl($video['galleryPhoto']) }}" class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">
                                            Edit entry
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        @if ($nextPageToken)
            <div class="mt-8 flex justify-center">
                <button
                    type="button"
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    wire:target="loadMore"
                    class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg inline-grid gap-1.5 px-4 py-2 text-sm ring-1 ring-gray-950/10 hover:bg-gray-50 dark:ring-white/20 dark:hover:bg-white/5 dark:text-white"
                >
                    Load more
                </button>
            </div>
        @endif
    @endif
</x-filament-panels::page>
