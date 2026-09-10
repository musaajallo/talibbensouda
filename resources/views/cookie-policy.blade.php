<x-app-layout>
@php $title = 'Cookie Policy' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Cookie Policy</span>
                </nav>
                <span class="page-hero__eyebrow">Cookie Policy</span>
                <h1 class="page-hero__title">How we use <span style="color:{{ '#c9a227' }}; font-style:italic;">cookies.</span></h1>
                <p class="page-hero__subtitle">
                    A plain-language explanation of the cookies this website stores on
                    your device, why we use them, and how you can manage your consent.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Policy content ──────────────────────────────────────────────────── --}}
    <div class="container">
        <div class="policy-layout"
            x-data="{
                active: 'quick-summary',
                init() {
                    const sections = document.querySelectorAll('.policy-section[id]');
                    const obs = new IntersectionObserver(entries => {
                        entries.forEach(e => { if (e.isIntersecting) this.active = e.target.id; });
                    }, { rootMargin: '-30% 0px -60% 0px' });
                    sections.forEach(s => obs.observe(s));
                }
            }"
        >

            {{-- ── Main content ── --}}
            <article class="policy-content" data-reveal>

                <p class="policy-content__updated">Last updated: 21 June 2026</p>

                {{-- Quick summary --}}
                <section class="policy-section" id="quick-summary">
                    <h2 class="policy-section__heading">Quick summary</h2>
                    <div class="policy-summary">
                        <strong>In short.</strong> We use a small number of essential cookies to keep the site working —
                        sign-in sessions, security tokens, and your theme preference. We don't currently run analytics
                        or marketing cookies. If we ever add analytics, we'll ask first and you can decline.
                    </div>
                </section>

                {{-- What are cookies? --}}
                <section class="policy-section" id="what-are-cookies">
                    <h2 class="policy-section__heading">What are cookies?</h2>
                    <p>
                        Cookies are tiny text files that websites place on your device when you visit them.
                        They let a site remember things between page loads — whether you're signed in,
                        what theme you've chosen, what's in your basket — without asking you again every click.
                    </p>
                    <p>
                        Some cookies are set by the site you're visiting (us). Others are set by third-party
                        services that the site relies on, such as payment processors. This page covers both.
                    </p>
                </section>

                {{-- Cookies we use --}}
                <section class="policy-section" id="cookies-we-use">
                    <h2 class="policy-section__heading">Cookies we use</h2>

                    <h3 class="policy-section__subheading">Essential cookies — always on</h3>
                    <p>
                        These cookies are required for the site to function. You can't opt out of them without
                        breaking core features (sign-in, form submissions, theme memory). They don't track you
                        across other sites and they don't carry marketing identifiers.
                    </p>
                    <ul>
                        <li><strong>Session cookie</strong> — keeps you signed in between pages while you're using the site. Cleared when you sign out or close your browser.</li>
                        <li><strong>CSRF token</strong> — a short-lived security token that protects forms from cross-site request forgery. Without it, form submissions would fail.</li>
                        <li><strong>Theme preference</strong> — remembers whether you've chosen light or dark mode so you don't have to set it on every visit. Stored locally on your device.</li>
                        <li><strong>Cookie-consent state</strong> — records the choices you've made about non-essential cookies so we don't ask you again on every page.</li>
                    </ul>

                    <h3 class="policy-section__subheading">Analytics cookies — opt-in</h3>
                    <p>
                        Analytics cookies would help us understand which pages people read, which events
                        drive the most interest, and where the site is hard to use. <strong>We don't currently use any
                        analytics service.</strong> If we add one, it will be opt-in: you'll see a clear consent prompt and
                        the cookie will not be set unless you accept.
                    </p>

                    <h3 class="policy-section__subheading">Marketing cookies — we don't use any</h3>
                    <p>
                        We do not run advertising, retargeting, or social-media tracking pixels on this site.
                        There are no marketing cookies set by us.
                    </p>
                </section>

                {{-- Third-party services --}}
                <section class="policy-section" id="third-party-services">
                    <h2 class="policy-section__heading">Third-party services we rely on</h2>
                    <p>
                        A few essential workflows are handled by external providers. When you interact with
                        those workflows, the providers may set their own cookies under their own privacy
                        policies. We do not control those cookies and we receive no advertising data from them.
                    </p>
                    <ul>
                        <li><strong>Resend</strong> — sends transactional email (account verification, password reset, contact replies). Resend does not set cookies on this site, but processes email delivery on our behalf.</li>
                        <li><strong>Google Fonts</strong> — serves the Inter and Montserrat typefaces used on this site. Google may log the request IP. We load fonts directly; no tracking cookies are set by this.</li>
                    </ul>
                </section>

                {{-- Your rights --}}
                <section class="policy-section" id="your-rights">
                    <h2 class="policy-section__heading">Your rights</h2>
                    <p>
                        Under Gambian data-protection law and, where applicable, the EU General Data
                        Protection Regulation (GDPR), you have the following rights in respect of personal
                        data we hold about you:
                    </p>
                    <ul>
                        <li><strong>Right to access.</strong> Ask us for a copy of the data we hold about you.</li>
                        <li><strong>Right to rectification.</strong> Ask us to correct data that's wrong or out of date.</li>
                        <li><strong>Right to erasure.</strong> Ask us to delete your data, subject to lawful retention.</li>
                        <li><strong>Right to withdraw consent.</strong> Where we rely on your consent (e.g. analytics if added), you can withdraw it at any time.</li>
                        <li><strong>Right to object.</strong> Object to processing for direct marketing or where we rely on legitimate interests.</li>
                        <li><strong>Right to portability.</strong> Receive your data in a machine-readable format.</li>
                    </ul>
                    <p>
                        To exercise any of these rights, write to the address below. We'll respond within thirty days.
                    </p>
                </section>

                {{-- Managing cookies --}}
                <section class="policy-section" id="managing-cookies">
                    <h2 class="policy-section__heading">Managing cookies</h2>
                    <p>You can change your consent at any time by reopening the cookie settings panel:</p>

                    <button
                        class="cookie-reopen-btn"
                        @click="document.dispatchEvent(new CustomEvent('reopen-cookie-settings'))"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Reopen cookie settings
                    </button>

                    <p>
                        You can also block or delete cookies directly in your browser settings. Most browsers
                        let you refuse all cookies, accept only first-party cookies, or be prompted before
                        accepting any. Note that blocking essential cookies will prevent parts of this site from
                        working — sign-in and form submissions in particular.
                    </p>
                </section>

                {{-- Contact --}}
                <section class="policy-section" id="contact">
                    <h2 class="policy-section__heading">Contact</h2>
                    <p>Questions about this policy or about how we handle your personal data? Write to our privacy inbox:</p>
                    <p><a href="mailto:info@talibbensouda.gm">info@talibbensouda.gm</a></p>
                    <p>For general enquiries, use the <a href="{{ url('/contact') }}">contact page</a>.</p>
                </section>

            </article>

            {{-- ── Sticky TOC sidebar ── --}}
            <aside class="policy-toc" data-reveal data-reveal-delay="80">
                <p class="policy-toc__label">On this page</p>
                <ul class="policy-toc__list">
                    <li><a href="#quick-summary"      class="policy-toc__link" :class="{ 'is-active': active === 'quick-summary' }">Quick summary</a></li>
                    <li><a href="#what-are-cookies"   class="policy-toc__link" :class="{ 'is-active': active === 'what-are-cookies' }">What are cookies?</a></li>
                    <li><a href="#cookies-we-use"     class="policy-toc__link" :class="{ 'is-active': active === 'cookies-we-use' }">Cookies we use</a></li>
                    <li><a href="#third-party-services" class="policy-toc__link" :class="{ 'is-active': active === 'third-party-services' }">Third-party services</a></li>
                    <li><a href="#your-rights"        class="policy-toc__link" :class="{ 'is-active': active === 'your-rights' }">Your rights</a></li>
                    <li><a href="#managing-cookies"   class="policy-toc__link" :class="{ 'is-active': active === 'managing-cookies' }">Managing cookies</a></li>
                    <li><a href="#contact"             class="policy-toc__link" :class="{ 'is-active': active === 'contact' }">Contact</a></li>
                </ul>
            </aside>

        </div>
    </div>

</x-app-layout>
