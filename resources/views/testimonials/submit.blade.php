<x-app-layout title="Share Your Testimonial" styles="testimonial-submit" description="Share a testimonial about Talib Ahmed Bensouda's work in Kanifing.">

    {{-- ── Page Hero ───────────────────────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <span class="page-hero__eyebrow">Testimonial</span>
                <h1 class="page-hero__title">Share Your Testimonial</h1>
                <p class="page-hero__subtitle">
                    A few words about your experience with Talib Bensouda or Kanifing
                    Municipal Council — shown on the site once it's been reviewed.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Form / status ────────────────────────────────────────────────────── --}}
    <section class="section">
        <div class="container">
            <div class="testimonial-submit-wrap" data-reveal>

                @if ($testimonial->approved)
                    <div class="testimonial-card">
                        <div class="testimonial-success" role="status">
                            <div class="testimonial-success__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <h2 class="testimonial-success__title">Thank You</h2>
                            <p class="testimonial-success__text">
                                Your testimonial has already been reviewed, so it can no
                                longer be edited here. Thank you for sharing your experience.
                            </p>
                            <div class="testimonial-success__actions">
                                <a href="{{ url('/') }}" class="btn btn--gold">Go to Home</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="testimonial-card">

                        <h2 class="testimonial-card__heading">Your Testimonial</h2>
                        <p class="testimonial-card__sub">
                            @if ($testimonial->hasBeenSubmitted())
                                You've already submitted this — feel free to update it below
                                any time before it's reviewed.
                            @else
                                Fields marked <span style="color:#c9a227" aria-hidden="true">*</span> are required.
                            @endif
                        </p>

                        @if (session('submission_success'))
                        <div class="form-alert form-alert--success" role="status" style="margin-bottom:24px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            {{ session('submission_success') }}
                        </div>
                        @endif

                        @if (session('submission_locked'))
                        <div class="form-alert form-alert--error" role="alert" style="margin-bottom:24px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ session('submission_locked') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="form-alert form-alert--error" role="alert" style="margin-bottom:24px">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div>
                                <strong>Please fix the following:</strong>
                                <ul style="margin:4px 0 0 16px; padding:0; list-style:disc;">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('testimonials.submit.store', $testimonial->invite_token) }}" method="POST" novalidate>
                            @csrf
                            <x-honeypot />

                            <div class="form-group">
                                <label for="name">Your Name <span class="required" aria-hidden="true">*</span></label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $testimonial->name) }}"
                                    placeholder="Your name"
                                    required
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                    @error('name') aria-describedby="name-error" @enderror
                                    autocomplete="name"
                                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                >
                                @error('name')
                                    <span id="name-error" class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role">Role / Location <span style="font-weight:400; color:var(--muted)">(optional)</span></label>
                                <input
                                    type="text"
                                    id="role"
                                    name="role"
                                    value="{{ old('role', $testimonial->role) }}"
                                    placeholder="e.g. &quot;United States · 2022&quot;"
                                >
                            </div>

                            <div class="form-group">
                                <label for="quote">Your Testimonial <span class="required" aria-hidden="true">*</span></label>
                                <textarea
                                    id="quote"
                                    name="quote"
                                    rows="5"
                                    required
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('quote') ? 'true' : 'false' }}"
                                    @error('quote') aria-describedby="quote-error" @enderror
                                    placeholder="Share your experience…"
                                >{{ old('quote', $testimonial->quote) }}</textarea>
                                @error('quote')
                                    <span id="quote-error" class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="testimonial-card__submit">
                                <button type="submit" class="btn btn--gold btn--lg" style="width:100%">
                                    {{ $testimonial->hasBeenSubmitted() ? 'Update Testimonial' : 'Submit Testimonial' }}
                                </button>
                            </div>

                        </form>
                    </div>
                @endif

            </div>
        </div>
    </section>

</x-app-layout>
