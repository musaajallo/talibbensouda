<x-app-layout title="Get in Touch" styles="contact">
@php
    $general = app(\App\Settings\GeneralSettings::class);
    $social = app(\App\Settings\SocialSettings::class);
    $socialIcons = [
        'facebook' => ['Facebook', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>'],
        'x' => ['X (Twitter)', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
        'instagram' => ['Instagram', '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
        'youtube' => ['YouTube', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>'],
        'whatsapp' => ['WhatsApp', '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>'],
    ];
@endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="page-hero__crumb-current">Get in Touch</span>
                </nav>
                <span class="page-hero__eyebrow">Contact</span>
                <h1 class="page-hero__title">Get in Touch</h1>
                <p class="page-hero__subtitle">
                    Questions, press enquiries, partnership requests, or an offer to
                    volunteer — reach out and the team will get back to you.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Contact Form + Info ──────────────────────────────────────────────── --}}
    <section class="section" id="contact-form">
        <div class="container">
            <div class="contact-layout">

                {{-- ── Info panel ── --}}
                <div class="contact-info" data-reveal>

                    <span class="contact-info__eyebrow">Reach Us</span>
                    <h2 class="contact-info__title">We'd Love to Hear<br>From You</h2>
                    <p class="contact-info__desc">
                        Whether you have a question about the campaign, a press enquiry, or just
                        want to share your thoughts — fill in the form and someone from the team
                        will get back to you.
                    </p>

                    <div class="contact-info__items">

                        <div class="contact-info__item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <div>
                                <div class="contact-info__item-label">Email</div>
                                <div class="contact-info__item-value">
                                    <a href="mailto:{{ $general->contact_email }}">{{ $general->contact_email }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="contact-info__item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <div>
                                <div class="contact-info__item-label">Based in</div>
                                <div class="contact-info__item-value">{{ $general->based_in }}</div>
                            </div>
                        </div>

                    </div>

                    @if (count($social->links()))
                        <div class="contact-info__social-label">Follow Talib</div>
                        <div class="contact-info__social">
                            @foreach ($social->links() as $key => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="social-btn" aria-label="{{ $socialIcons[$key][0] }}">
                                    {!! $socialIcons[$key][1] !!}
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>

                {{-- ── Contact form ── --}}
                <div data-reveal data-reveal-delay="80">

                    @if(session('contact_success'))
                    <div class="form-alert form-alert--success" style="margin-bottom:24px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        {{ session('contact_success') }}
                    </div>
                    @endif

                    <div class="contact-form-card">
                        <h2 class="contact-form-card__heading">Send a Message</h2>
                        <p class="contact-form-card__sub">Fill in the form below and our team will respond within 48 hours.</p>

                        <form action="{{ route('contact.store') }}" method="POST" novalidate>
                            @csrf
                            <x-honeypot />

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="Your name"
                                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        autocomplete="name"
                                    >
                                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="your@email.com"
                                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        autocomplete="email"
                                    >
                                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="What is your message about?"
                                    class="{{ $errors->has('subject') ? 'is-invalid' : '' }}"
                                >
                                @error('subject')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="message">Message <span class="required">*</span></label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    placeholder="Write your message here…"
                                    class="{{ $errors->has('message') ? 'is-invalid' : '' }}"
                                    style="min-height: 140px;"
                                >{{ old('message') }}</textarea>
                                @error('message')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <button type="submit" class="btn btn--gold contact-form-card__submit">
                                Send Message
                            </button>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ── Volunteer CTA ──────────────────────────────────────────────────────── --}}
    <section class="join-cta" id="join">
        <div class="join-cta__inner container" data-reveal>

            <span class="join-cta__eyebrow" id="volunteer">Lend a Hand</span>
            <h2 class="join-cta__title">Want to<br>Volunteer?</h2>
            <p class="join-cta__desc">
                Community events, clean-ups, school drives and youth programmes across
                Kanifing always need people. Send a message with a little about
                yourself and where you're based, and the team will be in touch.
            </p>

            <div class="join-cta__actions">
                <a href="#contact-form" class="btn btn--navy join-cta__btn">
                    Send a Message
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
                <a href="{{ url('/events') }}" class="btn btn--ghost-navy">
                    See upcoming events
                </a>
            </div>

        </div>
    </section>

</x-app-layout>
