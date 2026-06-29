<x-app-layout>
@php $title = 'Event Registration' @endphp

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <a href="{{ url('/events') }}" style="color:rgba(255,255,255,0.4); transition:color 0.15s;" onmouseover="this.style.color='#c9a227'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">Events</a>
                    <span>/</span>
                    <span style="color:rgba(255,255,255,0.65)">Register</span>
                </nav>
                <span class="page-hero__eyebrow">Secure Your Spot</span>
                <h1 class="page-hero__title">Register for an Event</h1>
                <p class="page-hero__subtitle">
                    Join Talib Bensouda in person. Fill in the form
                    and we'll confirm your place and send you all the details.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Registration ─────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="register-layout">

                {{-- ── Left: Event info ──────────────────────────────────────── --}}
                <div class="reg-info" data-reveal="fade-right">

                    <span class="reg-info__eyebrow">Upcoming Events</span>
                    <h2 class="reg-info__title">Choose Your Event<br>and Register Below</h2>
                    <p class="reg-info__desc">
                        Talib Bensouda is hosting events across The Gambia
                        and the global diaspora in 2026. Select the event that works for you —
                        all are free to attend.
                    </p>

                    <div class="reg-event-list">

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">14</div>
                                <div class="reg-event__month">Jul</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇧</div>
                                <div class="reg-event__title">Diaspora Tour — London, UK</div>
                                <div class="reg-event__loc">Greater London, United Kingdom</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">22</div>
                                <div class="reg-event__month">Jul</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇺🇸</div>
                                <div class="reg-event__title">Diaspora Tour — New York, USA</div>
                                <div class="reg-event__loc">New York City, United States</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">05</div>
                                <div class="reg-event__month">Aug</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇪🇸</div>
                                <div class="reg-event__title">Diaspora Tour — Madrid, Spain</div>
                                <div class="reg-event__loc">Madrid, Spain</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">20</div>
                                <div class="reg-event__month">Sep</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">Annual Party Convention</div>
                                <div class="reg-event__loc">Banjul, The Gambia</div>
                            </div>
                        </div>

                        <div class="reg-event">
                            <div class="reg-event__date">
                                <div class="reg-event__day">01</div>
                                <div class="reg-event__month">Nov</div>
                            </div>
                            <div class="reg-event__body">
                                <div class="reg-event__flag">🇬🇲</div>
                                <div class="reg-event__title">National Campaign Launch</div>
                                <div class="reg-event__loc">Independence Stadium, Banjul</div>
                            </div>
                        </div>

                    </div>

                    <p class="reg-contact">
                        Questions? Email us at
                        <a href="mailto:events@talibbensouda.gm">events@talibbensouda.gm</a>
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
                            <h2 class="reg-success__title">You're Registered!</h2>
                            <p class="reg-success__text">
                                {{ session('success') }}
                                Keep an eye on your inbox — we'll send you the full event details
                                as they are confirmed.
                            </p>
                            <div class="reg-success__actions">
                                <a href="{{ url('/events') }}" class="btn btn--outline-navy">Back to Events</a>
                                <a href="{{ url('/') }}" class="btn btn--gold">Go to Home</a>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="reg-form-card">

                        <h2 class="reg-form-card__heading">Your Registration</h2>
                        <p class="reg-form-card__sub">All events are free. Fields marked <span style="color:#c9a227">*</span> are required.</p>

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
                                        placeholder="e.g. Fatou Ceesay"
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

                            {{-- Phone + Guests --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="+44 7700 900000"
                                        autocomplete="tel"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="guests">Number of Guests <span class="required">*</span></label>
                                    <input
                                        type="number"
                                        id="guests"
                                        name="guests"
                                        value="{{ old('guests', 1) }}"
                                        min="1"
                                        max="10"
                                        required
                                        class="{{ $errors->has('guests') ? 'is-invalid' : '' }}"
                                    >
                                    @error('guests')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Event selection --}}
                            <div class="form-group">
                                <label for="event">Select Event <span class="required">*</span></label>
                                <select
                                    id="event"
                                    name="event"
                                    required
                                    class="{{ $errors->has('event') ? 'is-invalid' : '' }}"
                                >
                                    <option value="" disabled {{ old('event', $preselect) ? '' : 'selected' }}>— Choose an event —</option>
                                    @foreach([
                                        'Diaspora Tour — London, UK (14 Jul 2026)',
                                        'Diaspora Tour — New York, USA (22 Jul 2026)',
                                        'Diaspora Tour — Madrid, Spain (05 Aug 2026)',
                                        'Annual Party Convention — Banjul (20 Sep 2026)',
                                        'National Campaign Launch — Banjul (01 Nov 2026)',
                                    ] as $option)
                                    <option value="{{ $option }}" {{ old('event', $preselect) === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('event')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Special requirements --}}
                            <div class="form-group">
                                <label for="requirements">Special Requirements <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <textarea
                                    id="requirements"
                                    name="requirements"
                                    rows="3"
                                    placeholder="Accessibility needs, dietary requirements, etc."
                                >{{ old('requirements') }}</textarea>
                            </div>

                            {{-- Message --}}
                            <div class="form-group">
                                <label for="message">Message <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="3"
                                    placeholder="Anything you'd like us to know..."
                                >{{ old('message') }}</textarea>
                            </div>

                            <p class="form-note" style="margin-top:16px">
                                By registering, you agree to receive event communications from
                                Talib Bensouda's team. We won't share your details with third parties.
                            </p>

                            <div class="reg-form-card__submit">
                                <button type="submit" class="btn btn--gold btn--lg" style="width:100%">
                                    Register Now
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
