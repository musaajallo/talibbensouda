{{-- Opens the video on youtube.com in a new tab. Expects $videoId. --}}
<a
    href="https://www.youtube.com/watch?v={{ urlencode($videoId) }}"
    target="_blank"
    rel="noopener"
    class="fi-btn fi-btn-size-sm relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 rounded-lg inline-grid gap-1.5 px-3 py-2 text-sm ring-1 ring-gray-950/10 hover:bg-gray-50 dark:ring-white/20 dark:hover:bg-white/5 dark:text-white mt-2 w-full"
>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
    </svg>
    Open on YouTube
</a>
