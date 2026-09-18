@php
    $videoId = $getRecord()?->youtube_video_id;
@endphp

{{-- The YouTube video behind a Gallery entry. No autoplay — this is an edit
     screen, not a viewing one. The video itself lives on YouTube: nothing is
     uploaded or stored here, so there is no file to replace, only the
     caption / category / publish settings below. --}}
@if ($videoId)
    <div class="space-y-3">
        <div class="aspect-video w-full overflow-hidden rounded-lg bg-black">
            <iframe
                class="h-full w-full"
                src="https://www.youtube-nocookie.com/embed/{{ urlencode($videoId) }}"
                title="YouTube video"
                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
            ></iframe>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
            <p class="text-gray-500 dark:text-gray-400">
                Hosted on YouTube · video ID <code class="font-mono text-gray-950 dark:text-white">{{ $videoId }}</code>
            </p>

            <a
                href="https://www.youtube.com/watch?v={{ urlencode($videoId) }}"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-1.5 font-medium text-primary-600 hover:underline dark:text-primary-400"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Open on YouTube
            </a>
        </div>
    </div>
@else
    <p class="text-sm text-gray-500 dark:text-gray-400">This entry has no YouTube video ID.</p>
@endif
