@php
    $general = app(\App\Settings\GeneralSettings::class);
    $social = app(\App\Settings\SocialSettings::class);
    $chrome = app(\App\Settings\SiteChromeSettings::class);

    $brandName = $general->site_name ?: 'Talib Bensouda';

    // Inline SVG per platform — only links with a URL set in the panel render.
    $socialIcons = [
        'facebook' => ['Facebook', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>'],
        'x' => ['X (Twitter)', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
        'instagram' => ['Instagram', '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
        'youtube' => ['YouTube', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>'],
        'whatsapp' => ['WhatsApp', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $title = filled($title ?? null) && $title !== $brandName ? $brandName . ' — ' . $title : $brandName; @endphp
    <title>{{ $title }}</title>
    @include('partials.seo')
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0d1b38" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ $general->faviconUrl() }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/inter-latin.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/montserrat-latin.woff2') }}" crossorigin>
    <script @cspNonce>document.documentElement.classList.add('js');document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'light')</script>
    @vite(array_filter(['resources/sass/frontend/core.scss', $styleEntry(), 'resources/js/frontend.js']))
    @stack('head')
</head>
<body>

    <header class="site-header" x-data="{ open: false, dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', v => { document.documentElement.setAttribute('data-theme', v ? 'dark' : 'light'); localStorage.setItem('theme', v ? 'dark' : 'light'); })" :class="{ 'is-menu-open': open }" @keydown.escape.window="open = false" x-effect="document.body.classList.toggle('has-menu-open', open)">
        <div class="site-nav container">

            <a href="{{ url('/') }}" class="site-nav__brand" @click="open = false">{{ $brandName }}</a>

            <nav class="site-nav__menu" :class="{ 'is-open': open }" aria-label="Primary">
                <ul class="site-nav__links">
                    <li><a href="{{ url('/') }}"              class="site-nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a></li>
                    <li><a href="{{ url('/about') }}"         class="site-nav__link {{ request()->is('about') ? 'is-active' : '' }}">About</a></li>
                    <li><a href="{{ url('/peoples-mayor') }}" class="site-nav__link {{ request()->is('peoples-mayor') ? 'is-active' : '' }}">The People's Mayor</a></li>
                    <li><a href="{{ url('/gallery') }}"       class="site-nav__link {{ request()->is('gallery') ? 'is-active' : '' }}">Gallery</a></li>
                    <li><a href="{{ url('/events') }}"        class="site-nav__link {{ request()->is('events') ? 'is-active' : '' }}">Events</a></li>
                    <li><a href="{{ url('/giving-back') }}"   class="site-nav__link {{ request()->is('giving-back') ? 'is-active' : '' }}">Giving Back</a></li>
                    <li><a href="{{ url('/contact') }}"       class="site-nav__link {{ request()->is('contact') ? 'is-active' : '' }}">Get in Touch</a></li>
                </ul>

                @if ($chrome->join_party_url)
                    <a href="{{ $chrome->join_party_url }}" target="_blank" rel="noopener" class="btn btn--gold site-nav__menu-cta">{{ $chrome->join_party_label }}</a>
                @endif
            </nav>

            <div class="site-nav__controls">
                @if ($chrome->join_party_url)
                    <a href="{{ $chrome->join_party_url }}" target="_blank" rel="noopener" class="btn btn--gold nav-cta">{{ $chrome->join_party_label }}</a>
                @endif

                <button class="theme-toggle" @click="dark = !dark" :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>

                <button class="nav-toggle" @click="open = !open" :aria-expanded="open.toString()" aria-label="Toggle navigation">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </header>

    <main>{{ $slot }}</main>

    {{-- ── Cookie consent banner ── --}}
    <div
        x-data="{
            show: false,
            screen: 'main',
            analytics: false,
            init() {
                const stored = localStorage.getItem('tb_cookie_consent');
                if (!stored) {
                    setTimeout(() => this.show = true, 900);
                }
                document.addEventListener('reopen-cookie-settings', () => {
                    this.screen = 'main';
                    this.show = true;
                });
            },
            acceptAll() {
                this.save({ essential: true, analytics: true });
            },
            essentialOnly() {
                this.save({ essential: true, analytics: false });
            },
            saveSettings() {
                this.save({ essential: true, analytics: this.analytics });
            },
            save(prefs) {
                localStorage.setItem('tb_cookie_consent', JSON.stringify({ v: 1, ...prefs }));
                this.show = false;
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="cookie-banner"
        role="dialog"
        aria-label="Cookie consent"
        x-cloak
    >
        {{-- Main panel --}}
        <div class="cookie-banner__main" x-show="screen === 'main'">
            <h2 class="cookie-banner__heading">We use cookies</h2>
            <p class="cookie-banner__desc">
                Essential cookies keep the site working. We'd also like to set
                analytics cookies to learn what's working — only with your consent.
            </p>
            <a href="{{ url('/cookies') }}" class="cookie-banner__link">Read our cookie policy</a>
            <div class="cookie-banner__actions">
                <button class="cookie-banner__btn cookie-banner__btn--accept" @click="acceptAll()">Accept all</button>
                <button class="cookie-banner__btn cookie-banner__btn--essential" @click="essentialOnly()">Essential only</button>
                <button class="cookie-banner__btn cookie-banner__btn--settings" @click="screen = 'settings'">Settings</button>
            </div>
        </div>

        {{-- Settings panel --}}
        <div class="cookie-banner__settings" x-show="screen === 'settings'" x-cloak>
            <h2 class="cookie-banner__settings-heading">Cookie settings</h2>

            <div class="cookie-banner__cookie-row">
                <div class="cookie-banner__cookie-header">
                    <span class="cookie-banner__cookie-name">Essential cookies</span>
                    <span class="cookie-banner__always-on">Always on</span>
                </div>
                <p class="cookie-banner__cookie-desc">Required for the site to function — sign-in, forms, theme preference.</p>
            </div>

            <div class="cookie-banner__cookie-row">
                <div class="cookie-banner__cookie-header">
                    <span class="cookie-banner__cookie-name">Analytics cookies</span>
                    <label class="cookie-banner__toggle">
                        <input type="checkbox" x-model="analytics">
                        <span class="cookie-banner__toggle-track"></span>
                    </label>
                </div>
                <p class="cookie-banner__cookie-desc">Help us understand how pages are used so we can improve the site.</p>
            </div>

            <div class="cookie-banner__actions">
                <button class="cookie-banner__btn cookie-banner__btn--save" @click="saveSettings()">Save settings</button>
                <button class="cookie-banner__btn cookie-banner__btn--back" @click="screen = 'main'">Back</button>
            </div>
        </div>
    </div>

    {{-- Scroll to top --}}
    <button
        x-data="{ show: false }"
        x-show="show"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        @scroll.window="show = window.scrollY > 400"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="scroll-top"
        aria-label="Back to top"
        x-cloak
    >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>

    <footer class="site-footer">
        <div class="container">

            <p class="site-footer__tagline">{{ $chrome->footer_tagline }}</p>

            <ul class="site-footer__links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About</a></li>
                <li><a href="{{ url('/peoples-mayor') }}">The People's Mayor</a></li>
                <li><a href="{{ url('/gallery') }}">Gallery</a></li>
                <li><a href="{{ url('/events') }}">Events</a></li>
                <li><a href="{{ url('/giving-back') }}">Giving Back</a></li>
                <li><a href="{{ url('/contact') }}">Get in Touch</a></li>
            </ul>

            <ul class="site-footer__secondary-links">
                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ url('/cookies') }}">Cookie Policy</a></li>
                <li><a href="{{ url('/sitemap') }}">Sitemap</a></li>
            </ul>

            <div class="site-footer__contact">
                <div class="site-footer__contact-label">Contact</div>
                <a href="mailto:{{ $general->contact_email }}">{{ $general->contact_email }}</a>
            </div>

            @if (count($social->links()))
                <div class="site-footer__social">
                    @foreach ($social->links() as $key => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="social-btn" aria-label="{{ $socialIcons[$key][0] }}">
                            {!! $socialIcons[$key][1] !!}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="site-footer__bottom">
                <p>&copy; {{ date('Y') }} {{ $chrome->copyright_name }}. All rights reserved.</p>
            </div>

        </div>
    </footer>

</body>
</html>
