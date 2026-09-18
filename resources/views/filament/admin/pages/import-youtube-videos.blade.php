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
        <div class="flex items-center gap-3">
            <div class="fi-input-wrp flex-1 max-w-md rounded-lg shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20">
                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search this channel's videos by title…"
                    class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-sm text-gray-950 outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-500"
                />
            </div>
            <x-filament::loading-indicator wire:loading wire:target="search, loadVideos, loadMore" class="h-5 w-5 text-gray-400" />
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($videos as $video)
                @php $imported = $this->alreadyImported($video['id']); @endphp
                <x-filament::card class="flex flex-col gap-3">
                    <div class="relative aspect-video overflow-hidden rounded-lg bg-gray-100 dark:bg-white/5">
                        @if ($video['thumbnail'])
                            <img src="{{ $video['thumbnail'] }}" alt="" class="h-full w-full object-cover">
                        @endif
                        <span class="absolute inset-0 flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10 text-white drop-shadow"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-950 dark:text-white line-clamp-2">{{ $video['title'] }}</p>
                        @if ($video['published_at'])
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ \Illuminate\Support\Carbon::parse($video['published_at'])->format('j M Y') }}
                            </p>
                        @endif
                    </div>

                    @if ($imported)
                        <x-filament::badge color="success" icon="heroicon-o-check-circle">Already in the Gallery</x-filament::badge>
                    @else
                        <button
                            type="button"
                            wire:click="mountAction('addToGallery', { videoId: '{{ $video['id'] }}', title: @js($video['title']) })"
                            class="fi-btn fi-btn-size-sm relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg fi-color-primary fi-btn-color-primary inline-grid gap-1.5 fi-ac-btn-action px-3 py-2 text-sm bg-custom-600 text-white hover:bg-custom-500"
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                        >
                            Add to Gallery
                        </button>
                    @endif
                </x-filament::card>
            @empty
                <div class="col-span-full">
                    <x-filament::empty-state
                        icon="heroicon-o-video-camera"
                        heading="No videos found"
                        :description="filled($search) ? 'No titles on the channel match that search.' : 'Nothing came back from the channel — check the handle in config/services.php.'"
                    />
                </div>
            @endforelse
        </div>

        @if ($nextPageToken)
            <div class="mt-6 flex justify-center">
                <button
                    type="button"
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    wire:target="loadMore"
                    class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg inline-grid gap-1.5 px-4 py-2 text-sm ring-1 ring-gray-950/10 hover:bg-gray-50 dark:ring-white/20 dark:hover:bg-white/5"
                >
                    Load more
                </button>
            </div>
        @endif
    @endif
</x-filament-panels::page>
