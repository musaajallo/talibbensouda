<div class="aspect-video w-full overflow-hidden rounded-lg bg-black">
    @if ($videoId)
        {{-- autoplay=1 — opening this modal is itself the click that starts it;
             allow="autoplay" is what actually lets the browser honour it. --}}
        <iframe
            class="h-full w-full"
            src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?autoplay=1"
            title="YouTube video preview"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
        ></iframe>
    @endif
</div>

@if ($videoId)
    <a
        href="{{ $watchUrl }}"
        target="_blank"
        rel="noopener"
        class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        Open on YouTube
    </a>
@endif
