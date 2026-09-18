@php
    $chrome = app(\App\Settings\SiteChromeSettings::class);
    // $errors is normally shared globally by ShareErrorsFromSession, but a
    // few tests render a page's view directly (bypassing that middleware) to
    // check ITS OWN form's error markup — this component still needs to
    // degrade gracefully when $errors isn't in scope at all.
    $popupErrors = ($errors ?? null)?->getBag('campaignSignup') ?? new \Illuminate\Support\MessageBag;
@endphp
@if ($chrome->signup_popup_enabled)
    <div
        x-data="{
            show: false,
            delaySeconds: {{ max(0, (int) $chrome->signup_popup_delay_seconds) }},
            submitted: {{ session('campaign_signup_success') ? 'true' : 'false' }},
            hasErrors: {{ $popupErrors->any() ? 'true' : 'false' }},
            init() {
                if (this.submitted || this.hasErrors) {
                    this.show = true;
                    if (this.submitted) {
                        try { localStorage.setItem('tb_signup_popup_seen', '1'); } catch (e) {}
                    }
                    return;
                }
                let seen = false;
                try { seen = localStorage.getItem('tb_signup_popup_seen') === '1'; } catch (e) {}
                if (seen) return;

                this.startEngagementClock();
            },
            // Asking for details before someone has had a chance to use the
            // site is a bad first impression, so the pop-up only becomes
            // eligible after `delaySeconds` of ENGAGED time, totalled across
            // pages in localStorage (a per-page timer would restart on every
            // navigation and almost never fire). A second only counts while
            // the tab is visible and the visitor has scrolled, tapped, typed
            // or moved the mouse in the last 30 seconds, so a tab left open
            // in the background isn't 'browsing'.
            startEngagementClock() {
                const KEY = 'tb_signup_popup_elapsed';
                let elapsed = 0;
                try { elapsed = parseInt(localStorage.getItem(KEY) || '0', 10) || 0; } catch (e) {}
                if (elapsed >= this.delaySeconds) { this.whenBannerCleared(); return; }

                let lastActive = Date.now();
                const markActive = () => { lastActive = Date.now(); };
                ['scroll', 'pointermove', 'pointerdown', 'keydown', 'touchstart'].forEach((evt) => {
                    window.addEventListener(evt, markActive, { passive: true });
                });

                const timer = setInterval(() => {
                    if (document.visibilityState !== 'visible' || Date.now() - lastActive > 30000) return;
                    elapsed += 1;
                    try { localStorage.setItem(KEY, String(elapsed)); } catch (e) {}
                    if (elapsed >= this.delaySeconds) {
                        clearInterval(timer);
                        this.whenBannerCleared();
                    }
                }, 1000);
            },
            // Never stack with the cookie banner (layouts/app.blade.php) — it
            // sits above this dialog (z-index 9000) and would cover half the
            // form. If it's still unanswered, wait for its
            // 'cookie-consent-saved' event.
            whenBannerCleared() {
                let answered = true;
                try { answered = !!localStorage.getItem('tb_cookie_consent'); } catch (e) {}

                if (answered) {
                    this.show = true;
                } else {
                    window.addEventListener('cookie-consent-saved', () => {
                        setTimeout(() => { this.show = true; }, 1500);
                    }, { once: true });
                }
            },
            close() {
                this.show = false;
                try { localStorage.setItem('tb_signup_popup_seen', '1'); } catch (e) {}
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="signup-popup"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $chrome->signup_popup_heading }}"
        @keydown.escape.window="if (show) close()"
        x-cloak
    >
        <div class="signup-popup__backdrop" @click="close()"></div>

        <div class="signup-popup__card" x-trap="show">
            <button type="button" class="signup-popup__close" @click="close()" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <div class="signup-popup__image" style="background-image: url('{{ asset('images/hero-rally.webp') }}')"></div>

            <div class="signup-popup__body">
                @if (session('campaign_signup_success'))
                    <div class="signup-popup__success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <p>{{ session('campaign_signup_success') }}</p>
                    </div>
                @else
                    <h2 class="signup-popup__heading">{{ $chrome->signup_popup_heading }}</h2>
                    <p class="signup-popup__desc">{{ $chrome->signup_popup_body }}</p>

                    <form action="{{ route('campaign-signup.store') }}" method="POST" novalidate>
                        @csrf
                        <x-honeypot />

                        <div class="form-group">
                            <label for="popup_name">Full name <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" id="popup_name" name="popup_name" value="{{ old('popup_name') }}" required maxlength="150"
                                   class="{{ $popupErrors->has('popup_name') ? 'is-invalid' : '' }}">
                            @if ($popupErrors->has('popup_name'))
                                <span class="field-error">{{ $popupErrors->first('popup_name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="popup_phone">Phone number <span class="required" aria-hidden="true">*</span></label>
                            <input type="tel" id="popup_phone" name="popup_phone" value="{{ old('popup_phone') }}" required maxlength="30"
                                   class="{{ $popupErrors->has('popup_phone') ? 'is-invalid' : '' }}">
                            @if ($popupErrors->has('popup_phone'))
                                <span class="field-error">{{ $popupErrors->first('popup_phone') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="popup_location">Town or area</label>
                            <input type="text" id="popup_location" name="popup_location" value="{{ old('popup_location') }}" maxlength="150">
                        </div>

                        <label class="signup-popup__consent">
                            <input type="checkbox" name="popup_wants_updates" value="1" {{ old('popup_wants_updates') ? 'checked' : '' }}>
                            <span>Send me campaign updates</span>
                        </label>

                        <button type="submit" class="btn btn--gold btn--lg signup-popup__submit">
                            {{ $chrome->signup_popup_button_label }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endif
