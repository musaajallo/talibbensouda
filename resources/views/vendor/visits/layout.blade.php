<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics — @yield('title', 'Dashboard')</title>
    {{--
        This dashboard (fomvasss/laravel-visits) ships its own Tailwind-CDN
        layout — see App\Http\Middleware\ScopeCspForAnalyticsDashboard for why
        it runs under a relaxed CSP instead of the site's strict nonce policy.
        Overriding just this one file (Laravel's standard
        resources/views/vendor/<namespace>/... convention — no fork, no
        vendor:publish) reskins the shared chrome to match the Filament admin
        theme (resources/css/filament/admin/theme.css): same fonts, same navy
        primary, same brand wordmark, light by default. The individual
        dashboard pages (index/campaigns/sessions/...) are untouched — their
        own card/table/chart markup still renders with the package's default
        Tailwind utility classes, which is why this stops short of a pixel-
        perfect match.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
        // Light by default, matching the admin panel's own
        // defaultThemeMode(Light) — only a previous explicit toggle (not the
        // OS's colour-scheme preference) switches this to dark.
        if (localStorage.getItem('visits-theme') === 'dark') {
            document.documentElement.classList.replace('light', 'dark');
        }
    </script>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/inter-latin.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/montserrat-latin.woff2') }}" crossorigin>
    <style>
        @font-face {
            font-family: Inter;
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url('{{ asset('fonts/inter-latin.woff2') }}') format('woff2');
        }
        @font-face {
            font-family: Montserrat;
            font-style: normal;
            font-weight: 700 900;
            font-display: swap;
            src: url('{{ asset('fonts/montserrat-latin.woff2') }}') format('woff2');
        }

        :root {
            --navy-900: #080f20;
            --navy-700: #0B142E;
            --navy-600: #142045;
            --gold-600: #9B172B;
            --gold-500: #BD2038;
            --gold-200: #F17689;
        }

        body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        .analytics-brand { font-family: Montserrat, Inter, ui-sans-serif, system-ui, sans-serif; }

        /* The handful of default Tailwind-blue links/accents in the vendor
           views (coordinate links, a couple of highlighted values) — remapped
           to the site's own palette instead of leaving Tailwind's default
           blue, which reads visibly off-brand next to the rest of the panel. */
        .text-blue-600 { color: var(--gold-600) !important; }
        .dark .text-blue-400 { color: var(--gold-200) !important; }
    </style>
</head>
<body class="bg-[#FAFAF9] dark:bg-[#080f20] min-h-screen text-sm text-stone-800 dark:text-stone-200 transition-colors">

<nav class="bg-white dark:bg-[#0B142E] border-b border-stone-200 dark:border-white/10 px-6 py-3 flex items-center gap-5">
    <a href="{{ url('/admin') }}" class="flex items-center gap-1.5 text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white transition-colors" title="Back to the admin panel">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" /></svg>
        Admin
    </a>

    <span class="h-5 w-px bg-stone-200 dark:bg-white/10"></span>

    <a href="{{ route('visits.index') }}" class="analytics-brand flex items-baseline gap-1.5 shrink-0">
        <span class="text-base font-extrabold tracking-tight text-[#0B142E] dark:text-white">Talib Bensouda</span>
        <span class="text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-[#BD2038] dark:text-[#F17689]">Analytics</span>
    </a>

    <a href="{{ route('visits.index') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Overview</a>
    <a href="{{ route('visits.campaigns') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Campaigns</a>
    <a href="{{ route('visits.sessions') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Sessions</a>
    <a href="{{ route('visits.visitors') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Visitors</a>
    <a href="{{ route('visits.me') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Whoami</a>
    @if(\Illuminate\Support\Facades\Route::has('visits.live'))
        <a href="{{ route('visits.live') }}" class="text-xs font-medium text-stone-500 dark:text-stone-400 hover:text-[#0B142E] dark:hover:text-white">Live</a>
    @endif
    <span class="text-stone-400 dark:text-stone-500 text-xs">{{ config('app.env') }}</span>
    <div class="ml-auto flex items-center">
        <button onclick="toggleTheme()"
            aria-label="Toggle dark mode"
            class="flex items-center justify-center size-8 rounded-full border border-stone-200 dark:border-white/10 text-stone-500 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-white/5 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 dark:hidden"><path d="M10 2a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 2ZM10 15a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 15ZM10 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM15.657 5.404a.75.75 0 1 0-1.06-1.06l-1.061 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM6.464 14.596a.75.75 0 1 0-1.06-1.06l-1.061 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM18 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 18 10ZM5 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 5 10ZM14.596 15.657a.75.75 0 0 0 1.06-1.06l-1.06-1.061a.75.75 0 1 0-1.06 1.06l1.06 1.06ZM5.404 6.464a.75.75 0 0 0 1.06-1.06l-1.06-1.061a.75.75 0 1 0-1.061 1.06l1.06 1.06Z"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 hidden dark:block"><path fill-rule="evenodd" d="M7.455 2.004a.75.75 0 0 1 .26.77 7 7 0 0 0 9.958 7.967.75.75 0 0 1 1.067.853A8.5 8.5 0 1 1 6.647 1.921a.75.75 0 0 1 .808.083Z" clip-rule="evenodd" /></svg>
        </button>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-6">
    @yield('content')
</main>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.replace('dark', 'light');
            localStorage.setItem('visits-theme', 'light');
        } else {
            html.classList.replace('light', 'dark');
            localStorage.setItem('visits-theme', 'dark');
        }
    }
</script>

@stack('scripts')

</body>
</html>
