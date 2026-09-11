<x-app-layout title="Privacy Policy" styles="cookie-policy">

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Privacy Policy</span>
                </nav>
                <span class="page-hero__eyebrow">Privacy Policy</span>
                <h1 class="page-hero__title">Your data, <span style="color:#c9a227; font-style:italic;">handled honestly.</span></h1>
                <p class="page-hero__subtitle">
                    What personal information we collect, why we collect it,
                    how we use it, and the choices you have over it.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Policy content ──────────────────────────────────────────────────── --}}
    <div class="container">
        <div class="policy-layout"
            x-data="{
                active: 'who-we-are',
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

                {{-- Who we are --}}
                <section class="policy-section" id="who-we-are">
                    <h2 class="policy-section__heading">Who we are</h2>
                    <div class="policy-summary">
                        <strong>In short.</strong> This website is operated by the team of Talib Bensouda,
                        Lord Mayor of the Kanifing Municipal Council. We are the data controller
                        for any personal information you submit through this site.
                    </div>
                    <p>
                        If you have any questions about this policy or about how we handle your data,
                        contact us at <a href="mailto:info@talibahmedbensouda.com">info@talibahmedbensouda.com</a>.
                    </p>
                </section>

                {{-- What we collect --}}
                <section class="policy-section" id="what-we-collect">
                    <h2 class="policy-section__heading">What we collect and why</h2>

                    <h3 class="policy-section__subheading">Information you give us</h3>
                    <ul>
                        <li><strong>Contact form submissions</strong> — your name, email address, and the message you write. We use this solely to respond to your enquiry.</li>
                        <li><strong>Event registrations</strong> — your name and email when you register for a campaign event. We use this to confirm your place and send event updates.</li>
                        <li><strong>Account creation</strong> — name and email if you create a portal account. This is used to authenticate you and manage your access.</li>
                    </ul>

                    <h3 class="policy-section__subheading">Information collected automatically</h3>
                    <ul>
                        <li><strong>Server logs</strong> — your IP address, browser type, pages visited, and timestamps. These are retained for up to 30 days for security and diagnostic purposes and are not used for profiling.</li>
                        <li><strong>Cookies and local storage</strong> — see our <a href="{{ url('/cookies') }}">Cookie Policy</a> for the full breakdown. In short: session tokens, CSRF protection, theme preference, and (with your consent) analytics.</li>
                    </ul>

                    <h3 class="policy-section__subheading">Information we do not collect</h3>
                    <p>
                        We do not collect payment information, government-issued ID, or sensitive categories
                        of personal data (health, religion, ethnicity, political opinions beyond your
                        voluntary engagement with this campaign). We do not knowingly collect data from
                        children under 13.
                    </p>
                </section>

                {{-- Legal basis --}}
                <section class="policy-section" id="legal-basis">
                    <h2 class="policy-section__heading">Legal basis for processing</h2>
                    <p>We process your data on one of the following bases:</p>
                    <ul>
                        <li><strong>Consent</strong> — where you have actively opted in (e.g. analytics cookies, joining our mailing list).</li>
                        <li><strong>Legitimate interests</strong> — where processing is reasonably necessary to run and improve the site, and those interests are not overridden by your rights (e.g. server security logs).</li>
                        <li><strong>Contract</strong> — where processing is necessary to fulfil a commitment you've made (e.g. processing an event registration).</li>
                        <li><strong>Legal obligation</strong> — where we are required by law to retain certain records.</li>
                    </ul>
                </section>

                {{-- How we use it --}}
                <section class="policy-section" id="how-we-use-it">
                    <h2 class="policy-section__heading">How we use your information</h2>
                    <ul>
                        <li>Responding to messages sent through the contact form.</li>
                        <li>Confirming event registrations and sending event-related updates.</li>
                        <li>Authenticating portal accounts and maintaining account security.</li>
                        <li>Diagnosing technical issues and protecting against misuse.</li>
                        <li>Improving the site based on anonymised usage patterns (only if analytics consent is given).</li>
                    </ul>
                    <p>
                        We will not use your information for any purpose materially different from the
                        one for which it was collected without asking for fresh consent.
                    </p>
                </section>

                {{-- Sharing --}}
                <section class="policy-section" id="sharing">
                    <h2 class="policy-section__heading">Who we share it with</h2>
                    <p>
                        We do not sell, rent, or trade your personal data. We share it only with:
                    </p>
                    <ul>
                        <li><strong>Resend</strong> — our transactional email provider, for sending confirmation and reply emails. Data is processed under their Data Processing Agreement.</li>
                        <li><strong>Hosting provider</strong> — our web host processes server traffic data to serve the site. This provider is contractually bound to process data only on our instructions.</li>
                        <li><strong>Legal authorities</strong> — if required by a valid court order or applicable law in The Gambia.</li>
                    </ul>
                    <p>
                        No data is transferred outside The Gambia except where the recipient provides
                        equivalent data-protection safeguards (e.g. standard contractual clauses).
                    </p>
                </section>

                {{-- Retention --}}
                <section class="policy-section" id="retention">
                    <h2 class="policy-section__heading">How long we keep it</h2>
                    <ul>
                        <li><strong>Contact enquiries</strong> — up to 2 years from the date of last correspondence, then securely deleted.</li>
                        <li><strong>Event registrations</strong> — up to 6 months after the event date.</li>
                        <li><strong>Portal accounts</strong> — for as long as the account is active. Inactive accounts are deleted after 12 months of no sign-in, with prior notice.</li>
                        <li><strong>Server logs</strong> — 30 days, then automatically purged.</li>
                        <li><strong>Analytics data</strong> — if collected with consent, retained in aggregated, anonymised form only.</li>
                    </ul>
                </section>

                {{-- Your rights --}}
                <section class="policy-section" id="your-rights">
                    <h2 class="policy-section__heading">Your rights</h2>
                    <p>Under applicable data-protection law you have the right to:</p>
                    <ul>
                        <li><strong>Access</strong> the personal data we hold about you.</li>
                        <li><strong>Rectify</strong> inaccurate or incomplete data.</li>
                        <li><strong>Erase</strong> your data (the "right to be forgotten"), subject to legal retention requirements.</li>
                        <li><strong>Restrict</strong> processing while a dispute is resolved.</li>
                        <li><strong>Object</strong> to processing based on legitimate interests or for direct marketing.</li>
                        <li><strong>Port</strong> your data to another service in a machine-readable format.</li>
                        <li><strong>Withdraw consent</strong> at any time where processing is consent-based, without affecting the lawfulness of prior processing.</li>
                    </ul>
                    <p>
                        To exercise any of these rights, email <a href="mailto:info@talibahmedbensouda.com">info@talibahmedbensouda.com</a>.
                        We will respond within 30 days. If you believe we have mishandled your data
                        you have the right to lodge a complaint with the relevant data-protection authority in The Gambia.
                    </p>
                </section>

                {{-- Security --}}
                <section class="policy-section" id="security">
                    <h2 class="policy-section__heading">Security</h2>
                    <p>
                        We take reasonable technical and organisational measures to protect your personal
                        data against unauthorised access, alteration, disclosure, or destruction. These
                        include HTTPS encryption on all pages, hashed password storage, CSRF protection
                        on all forms, and restricted access to production systems.
                    </p>
                    <p>
                        No method of transmission over the internet is 100% secure. If you discover a
                        security vulnerability, please report it responsibly to
                        <a href="mailto:info@talibahmedbensouda.com">info@talibahmedbensouda.com</a>.
                    </p>
                </section>

                {{-- Changes --}}
                <section class="policy-section" id="changes">
                    <h2 class="policy-section__heading">Changes to this policy</h2>
                    <p>
                        We may update this policy from time to time. Significant changes will be
                        communicated by updating the "Last updated" date at the top of this page.
                        We encourage you to review this page periodically.
                    </p>
                </section>

                {{-- Contact --}}
                <section class="policy-section" id="contact">
                    <h2 class="policy-section__heading">Contact</h2>
                    <p>Questions or requests about this policy:</p>
                    <p><a href="mailto:info@talibahmedbensouda.com">info@talibahmedbensouda.com</a></p>
                    <p>For general enquiries, use the <a href="{{ url('/contact') }}">contact page</a>.</p>
                </section>

            </article>

            {{-- ── Sticky TOC sidebar ── --}}
            <aside class="policy-toc" data-reveal data-reveal-delay="80">
                <p class="policy-toc__label">On this page</p>
                <ul class="policy-toc__list">
                    <li><a href="#who-we-are"      class="policy-toc__link" :class="{ 'is-active': active === 'who-we-are' }">Who we are</a></li>
                    <li><a href="#what-we-collect" class="policy-toc__link" :class="{ 'is-active': active === 'what-we-collect' }">What we collect</a></li>
                    <li><a href="#legal-basis"     class="policy-toc__link" :class="{ 'is-active': active === 'legal-basis' }">Legal basis</a></li>
                    <li><a href="#how-we-use-it"   class="policy-toc__link" :class="{ 'is-active': active === 'how-we-use-it' }">How we use it</a></li>
                    <li><a href="#sharing"          class="policy-toc__link" :class="{ 'is-active': active === 'sharing' }">Who we share it with</a></li>
                    <li><a href="#retention"        class="policy-toc__link" :class="{ 'is-active': active === 'retention' }">How long we keep it</a></li>
                    <li><a href="#your-rights"      class="policy-toc__link" :class="{ 'is-active': active === 'your-rights' }">Your rights</a></li>
                    <li><a href="#security"         class="policy-toc__link" :class="{ 'is-active': active === 'security' }">Security</a></li>
                    <li><a href="#changes"          class="policy-toc__link" :class="{ 'is-active': active === 'changes' }">Changes</a></li>
                    <li><a href="#contact"          class="policy-toc__link" :class="{ 'is-active': active === 'contact' }">Contact</a></li>
                </ul>
            </aside>

        </div>
    </div>

</x-app-layout>
