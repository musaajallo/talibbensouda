<x-app-layout>
@php $title = 'Register Your Interest' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <a href="{{ url('/events') }}" style="color:rgba(255,255,255,0.4); transition:color 0.15s;" onmouseover="this.style.color='#c9a227'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">Events</a>
                    <span>/</span>
                    <span style="color:rgba(255,255,255,0.65)">Register Interest</span>
                </nav>
                <span class="page-hero__eyebrow">Stay Informed</span>
                <h1 class="page-hero__title">Register Your Interest</h1>
                <p class="page-hero__subtitle">
                    Leave your details and we'll let you know first about upcoming
                    public events, openings and gatherings.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Registration ─────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="register-layout">

                {{-- ── Left: Recent milestones ───────────────────────────────── --}}
                <div class="reg-info" data-reveal="fade-right">

                    <span class="reg-info__eyebrow">Recently</span>
                    <h2 class="reg-info__title">What's Been<br>Happening</h2>
                    <p class="reg-info__desc">
                        A snapshot of recent openings and launches across Kanifing.
                        The full record is on the
                        <a href="{{ url('/events') }}">events &amp; milestones</a> page.
                    </p>

                    <div class="reg-event-list">

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">01</div>
                                <div class="reg-event__month">Jun</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">Digital Addresses for Policymaking Launched</div>
                                <div class="reg-event__loc">Kanifing Municipality · 2026</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">01</div>
                                <div class="reg-event__month">Jul</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">UN Deputy Secretary-General Visits Bakoteh Skills Centre</div>
                                <div class="reg-event__loc">Bakoteh · 2025</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">01</div>
                                <div class="reg-event__month">Jun</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">Bakau Multipurpose Facility Launched</div>
                                <div class="reg-event__loc">Bakau · 2025</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">01</div>
                                <div class="reg-event__month">Dec</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">Kanifing Municipal Library &amp; Innovation Hub Inaugurated</div>
                                <div class="reg-event__loc">Kanifing Municipality · 2024</div>
                            </div>
                        </div>

                    </div>

                    <p class="reg-contact">
                        Questions? Email
                        <a href="mailto:info@talibbensouda.gm">info@talibbensouda.gm</a>
                        or <a href="{{ url('/contact') }}">get in touch</a>.
                    </p>

                </div>

                {{-- ── Right: Form ────────────────────────────────────────────── --}}
                <div data-reveal="fade-left" data-reveal-delay="100">

                    @if (session('success'))
                    <div class="reg-form-card">
                        <div class="reg-success">
                            <div class="reg-success__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <h2 class="reg-success__title">You're on the List</h2>
                            <p class="reg-success__text">
                                {{ session('success') }}
                                We'll email you when the next public event is announced.
                            </p>
                            <div class="reg-success__actions">
                                <a href="{{ url('/events') }}" class="btn btn--outline-navy">Back to Events</a>
                                <a href="{{ url('/') }}" class="btn btn--gold">Go to Home</a>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="reg-form-card">

                        <h2 class="reg-form-card__heading">Your Details</h2>
                        <p class="reg-form-card__sub">Fields marked <span style="color:#c9a227">*</span> are required.</p>

                        @if ($errors->any())
                        <div class="form-alert form-alert--error" style="margin-bottom:24px">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div>
                                <strong>Please fix the following:</strong>
                                <ul style="margin:4px 0 0 16px; padding:0; list-style:disc;">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('events.register.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- Name + Email --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="Your name"
                                        required
                                        autocomplete="name"
                                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    >
                                    @error('name')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="you@example.com"
                                        required
                                        autocomplete="email"
                                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    >
                                    @error('email')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label for="phone">Phone Number <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="+220 …"
                                    autocomplete="tel"
                                >
                            </div>

                            {{-- Interest --}}
                            <div class="form-group">
                                <label for="event">What are you interested in? <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <input
                                    type="text"
                                    id="event"
                                    name="event"
                                    value="{{ old('event', $preselect) }}"
                                    placeholder="e.g. community events in Serrekunda, youth programmes…"
                                >
                            </div>

                            {{-- Message --}}
                            <div class="form-group">
                                <label for="message">Message <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="3"
                                    placeholder="Anything you'd like us to know…"
                                >{{ old('message') }}</textarea>
                            </div>

                            <p class="form-note" style="margin-top:16px">
                                We'll only use your details to send you event updates.
                                We won't share them with third parties.
                            </p>

                            <div class="reg-form-card__submit">
                                <button type="submit" class="btn btn--gold btn--lg" style="width:100%">
                                    Register Interest
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </button>
                            </div>

                        </form>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

</x-app-layout>
