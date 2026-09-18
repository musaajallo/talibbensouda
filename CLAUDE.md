# talibbensouda — working notes

Laravel 13 · PHP 8.4 · Filament 5 admin · SCSS + Alpine public site (no Tailwind on the
front end — but the **admin panel** does use Tailwind v4, scoped to its theme file only).
Public content is a mix of **spatie/laravel-settings** (page copy, edited via
Filament `SettingsPage`s) and **Eloquent models with Filament resources** (repeating content
with images). All images go through **spatie/laravel-medialibrary** on the local `public`
disk. Modelled on the sibling `umc` project.

## Layout of the admin

- Panel provider: `app/Providers/Filament/AdminPanelProvider.php`, mounted at `/admin`.
  Top navigation, campaign-navy primary (`#0d1b38`), custom brand theme at
  `resources/css/filament/admin/theme.css` (a Vite input, `->viteTheme(...)`; needs
  `@tailwindcss/vite` — already in `vite.config.js`). Branded split-screen login:
  `app/Filament/Admin/Auth/Login.php` + `resources/views/filament/admin/auth/*`. Brand
  wordmark: `resources/views/filament/admin/brand.blade.php` (falls back to a Montserrat
  wordmark; `GeneralSettings::$site_logo` overrides). Dashboard widgets:
  `app/Filament/Admin/Widgets/*` (QuickActions, SubmissionsOverview, ContentOverview, two
  latest-submission table widgets) — registered explicitly in the panel provider.
- **`APP_NAME` must be set in the Forge env** (`APP_NAME="Talib Bensouda"`) — the brand
  falls back to it, and Forge's default is `Laravel`.
- Resources: `app/Filament/Admin/Resources/<Name>/…` (Resource + `Schemas/<Name>Form.php` +
  `Tables/<Name>Table.php` + `Pages/*`). Content models: `Event`, `Milestone`, `GalleryPhoto`,
  `Project` (`summary` for the home card, full `description` + `metrics` for People's Mayor),
  `Testimonial`, `CommunityPhoto` (`group`: `municipality` = home + People's Mayor,
  `community-support` = Giving Back), `GivingProgramme`, plus the `ContactMessage` /
  `EventRegistration` inboxes. Seed-managed rows carry a stable `key` (or `slug`).
- **`Event` vs. `Milestone` — separate resources, deliberately.** `Event` is something
  scheduled that people can attend (a rally, a courtesy call) — it has a flyer, RSVP
  ("Register Your Interest"), calendar export and a public `/events/<slug>` page.
  `Milestone` is a past record-of-delivery fact (an opening, a launch, an election) — just
  a title, date, place and description, no detail page, no scheduling machinery. They used
  to be one `Event` table (badge `Kanifing` = milestone, `Campaign` = real event); split in
  2026-09 (see `database/migrations/2026_09_18_100000_create_milestones_table.php` and
  `..._100001_move_kmc_milestones_out_of_events_table.php`). Don't reintroduce a past,
  non-RSVPable "milestone" as an `Event` row — it belongs in `Milestone`. Every place that
  displays one or the other uses a **different UI component on purpose** — `.event-card`
  (rich, flyer + CTAs) or `.event-row` (compact list, theme-aware, `components/_event-row.scss`)
  for Events, `.milestone-card`/`.milestones-grid` (plain grid, no CTA,
  `components/_milestone-card.scss`) for Milestones — so the two never look interchangeable.
- **`Event` schedule**: the form only asks for one `event_date` + `start_time` / `end_time`
  (defaults 09:00–17:00). `App\Filament\Admin\Resources\Events\Concerns\HandlesEventSchedule`
  (used by `CreateEvent` + `EditEvent`) assembles those into the seven columns the model
  and the public pages actually read — `date_day`/`date_month`/`date_year` (display),
  `js_day`/`js_month` (0-indexed, feed the public calendar grid) and `ics_start`/`ics_end`
  (UTC `YYYYMMDDThhmmssZ`, the calendar export — The Gambia is UTC year-round, so no tz
  math). `is_upcoming` is a **published/visibility toggle, not a chronology flag** — an
  event stays `is_upcoming = true` after its date passes; whether it's actually upcoming or
  past is worked out from `ics_end` vs. `now()` wherever it's queried (see below). `badge`
  (the type pill) is a free label managed under `EventsPageSettings::event_types`, not a
  hardcoded enum (currently `['Kanifing', 'Campaign']`). `full_description` is a
  `RichEditor` (HTML); a model mutator wraps any legacy plain text in `<p>` on write. Every
  event has a flyer image (`Event::flyerImageUrl()`) — the real upload, or
  `App\Support\EventFlyerPlaceholder` generates an on-brand SVG on the fly from the
  title/type/date when none is set. `date_day` is a free string, not an integer — multi-day
  entries (courtesy-call weekends/weeks) use a range like `'16–20'`; `js_day`/`js_month` still
  take the range's first day for the calendar grid.
- **One-week public-visibility rule** (`Event::scopeVisibleUpcoming()`, 2026-09) — a
  scheduled event only appears *anywhere* on the site once `ics_start` is within a week of
  now. Not a data rule: further-out events stay in the database, fully editable in the
  panel, they just don't render publicly yet. This is the single source of truth for the
  cutoff — `EventController::index()`'s `$upcoming` (and the calendar, built from it),
  `show()`'s "related events", and the **homepage**'s "Upcoming Events" section
  (`welcome.blade.php`) all call this same scope, so changing the window only ever means
  editing one method. `Event::hiddenUpcomingCount()` is the companion query (events beyond
  the window) that feeds the `<x-hidden-events-teaser :count="…">` component — a one-line
  "+N more events already on the calendar — come back soon" nudge, shown on both `/events`
  and the homepage section. Both the homepage section and the events page's "Past Events"
  section hide entirely (no empty-state filler) when they'd have nothing to show *and*
  nothing to tease — see the `@if` right above each one in the Blade for the exact
  condition, since it's not simply "the list is empty" once the teaser is in play.
- **Events kill switch**: `EventsPageSettings::$hide_events_sections` (Page content →
  Events page → Visibility, 2026-09) hides Events everywhere on the public site — the
  homepage's "Upcoming Events" section, and `/events`' upcoming list, calendar, and Past
  Events section — leaving only Milestones visible on both. A display toggle only; no rows
  are touched, and `/events/{slug}` detail pages stay directly reachable regardless. When
  it's on, `EventController::index()` skips the Event queries entirely rather than running
  them and hiding the result (`$upcoming`/`$past` come back as empty collections,
  `$hiddenUpcomingCount` as `0`) — `$eventsHidden` is passed to the view either way, and the
  page-hero subtitle on `/events` also switches copy so it doesn't reference events that
  aren't shown.
- **`/events` page** (`EventController::index`) splits events into `$upcoming` (the
  scope above, ascending — the rich `.event-card` list + calendar) and `$past`
  (`ics_end < now()`, descending — the compact `.event-row` list, no flyer/RSVP), plus a
  wholly separate `$milestones` (`Milestone::published()`, descending by `occurred_on`,
  `.milestone-card` grid). Same idea feeds the **homepage**: "Upcoming Events" (directly
  below The People's Mayor) queries `Event`; "Recent Milestones" further down queries
  `Milestone`. Copy for the homepage section lives in `HomePageSettings::$upcoming_*` /
  `$milestones_*` via `ManageHomePage`. The 2026 pre-nomination campaign itinerary
  (courtesy calls by region + rallies) that populates `Event` lives in
  `EventSeeder::campaignItinerary()`, sourced from
  `docs/assets/campaign-itinerary/pre-nomination-itinerary-2026-09-18.jpeg`; the KMC record
  (openings, launches, elections, 2018–2026) that populates `Milestone` lives in
  `MilestoneSeeder`.
- **Content seeding:** `ContentSeeder` (events, milestones, projects, gallery, community
  photos, testimonials, giving-back programmes + `HeroSlidesSeeder`) runs on every deploy via
  `.forge/deploy.sh`. All of them `firstOrCreate` on the key — a missing row is created,
  an existing one is **never** touched, so panel edits survive re-deploys. `HeroSlidesSeeder`
  copies `public/images/hero-*.webp` onto the media disk and fills `home_page.hero_slides`
  so the slider is editable in the panel; it no-ops once slides are configured.
  `docs/Talib Legacy - The People's Mayor.md` is the source content brief (KMC records +
  public reporting) — the seeders are a curated summary of it, not a transcription. When
  adding seed content, check it against that doc first. Parks/stadium, international
  partnerships, flood prevention, governance/housing, and the Tekki Fii youth-fund
  sub-detail (first COVID-response cohort's named winners and amounts, plus the separate
  D1M "Andandorr–Tekki Fii" innovation fund) have all been added (`ProjectSeeder`).
  `GalleryPhotoSeeder` seeds 85 captions across Projects/Community/Events/Partners —
  see **Photo content** below for how those (and every Project/CommunityPhoto row) got
  a real image.
- Page-copy settings: `GeneralSettings`, `SocialSettings`, `SiteChromeSettings`,
  `HomePageSettings`, `PeoplesMayorPageSettings`, `GivingBackPageSettings`, `AboutPageSettings`,
  `EventsPageSettings` (currently just the event-type list).
  Multi-paragraph fields are stored blank-line-separated and split with the
  `App\Settings\Concerns\SplitsParagraphs` trait (`$page->paragraphs('bio_body')`).
- **Editing an existing settings array in a migration** (not adding a new key): the
  migrator's `update()` callback receives `stdClass` objects for array-of-object properties
  (`json_decode($json, false)`), not associative arrays — use `$item->title`, not
  `$item['title']`. Reading the same property back through the `Settings` class (i.e. normal
  app code) gives a plain array as usual; the mismatch is only inside a migration's closure.
  See `database/settings/2026_09_11_041203_add_fire_trucks_to_enforcement_card.php` and
  `..._041204_add_womens_congress_to_timeline.php`.
- Each public view reads its models + settings in a top `@php` block and degrades gracefully
  (a section hides when its collection is empty; images fall back to a placeholder tile;
  decorative SVG icons are cycled by index, not stored).
- Every public view opens with `<x-app-layout title="…" styles="<route>" description="…">`.
  `styles` picks the route CSS bundle (`resources/sass/frontend/pages-entry/<route>.scss`,
  also a Vite input) loaded on top of `core.scss`; `description` / `ogImage` feed
  `resources/views/partials/seo.blade.php` (meta, canonical, OG, Twitter card, JSON-LD
  Person + WebSite). See **Analytics** below for how page views are tracked — no
  script tag in the layout at all, it's server-side only.
- **Any component used on more than one route must live under
  `resources/sass/frontend/components/` and be `@use`d from `core.scss`** — not declared
  inside a single page's `pages/_<route>.scss`. `.section`, `.section-header`, `.cta-banner`,
  `.community-grid`, `.lightbox` all used to live only in `pages/_home.scss`, so every other
  page that reused that markup (which several do) rendered it completely unstyled. Fixed
  once (PR #29) by moving them to `components/`; don't reintroduce a page-scoped definition
  of something other pages also use. The shared photo grid + lightbox markup itself is a
  Blade component, `<x-community-photo-grid :photos="$collection" empty-message="…">` —
  used by the home page, People's Mayor, and Giving Back; reuse it rather than hand-rolling
  another grid.
- The contact / event-registration forms POST through the `throttle:public-forms`
  limiter (5/min, 40/day per IP — `AppServiceProvider`) and raise a Filament database
  notification to admins via `App\Support\Notifications\AdminAlert` (model observers).
  They still don't email anyone.
- Settings pages: `app/Filament/Admin/Pages/Manage*.php` extending `Filament\Pages\SettingsPage`,
  each bound to a class in `app/Settings/`. Groups: **System** (general, social, header/footer)
  and **Page content** (home, about, People's Mayor, Giving Back, Events). Every one `use`s the
  `Concerns\NormalisesSettingsData` trait — Filament dehydrates an empty `TextInput` to `null`,
  but the settings classes type most props as non-nullable `string`, so without the trait
  clearing an optional field 500s the save (`Cannot assign null to property … of type string`).
  Keep the trait on any new settings page. `tests/Feature/Admin/SettingsPagesTest.php` mounts
  and saves all eight.
- Roles: `spatie/laravel-permission`. Two roles — `admin`, `super-admin`. `super-admin` is
  also short-circuited by `Gate::before` in `AppServiceProvider`. Panel access requires one
  of those roles (`User::canAccessPanel`).
- `bezhansalleh/filament-shield` owns the Roles screen and generates per-resource
  permissions + policies.

## Recipes

### Add a content model with an admin UI

```bash
php artisan make:model Thing -m          # add slug + published_at columns; no image columns
php artisan make:filament-resource Thing --generate
php artisan shield:generate --all --panel=admin      # ALWAYS re-run after a new resource
```

- Model: `use InteractsWithMedia, LogsActivity` (+ `HasSlug` if it has a public URL). Add
  `use App\Support\Media\ResolvesPublicMediaUrl;` and expose `imageUrl()` built on
  `publicMediaUrl('<collection>')`.
- `registerMediaCollections()` → `addMediaCollection('photo')->singleFile()->useDisk('public')`.
- Form: `SpatieMediaLibraryFileUpload::make('photo')->collection('photo')->image()
  ->imageEditor()->responsiveImages()`.
- Register the generated permissions with the seeder: `RolesAndPermissionsSeeder` syncs
  `Permission::all()` onto both roles.

### Add an editable-copy page

```bash
php artisan make:setting ThingPageSettings                       # app/Settings/
php artisan make:settings-migration create_thing_page_settings   # database/settings/
php artisan make:filament-settings-page ManageThingPage "App\Settings\ThingPageSettings" --panel=admin
```

- The settings migration seeds each key with the value currently hardcoded in the Blade.
- Register the class in `config/settings.php` (`settings` array).
- **Do not** put `array<string,T>` PHPDoc shapes on settings array props — Spatie's parser
  chokes. Use bare `public array $foo = [];`.
- Settings-page images: plain `Filament\Forms\Components\FileUpload->image()->disk('public')
  ->directory('branding')`, stored as a nullable string, consumed via
  `Storage::disk('public')->url()` with an `asset()` fallback + `->exists()` guard (see
  `GeneralSettings::logoUrl()`).
- Blade reads it with `app(\App\Settings\ThingPageSettings::class)` in a top `@php` block —
  laravel-settings resolves as a per-request singleton, so multiple `@php` blocks are fine.

## Images in production

- Media disk is `public` (`MEDIA_DISK`), i.e. `storage/app/public` served through the
  `public/storage` symlink. `.forge/deploy.sh` rebuilds that symlink each deploy.
- **Optimiser binaries are required** or `spatie/laravel-image-optimizer` silently no-ops:
  `sudo apt install jpegoptim optipng pngquant gifsicle webp`.
- Conversions / responsive images run on the **queue** — a `queue:work` worker must be
  running (systemd or Supervisor). Deploy runs `queue:restart`.
- HTTPS: `AppServiceProvider::boot()` calls `URL::forceScheme('https')` and re-points
  `filesystems.disks.public.url` at `secure_asset('storage')` in production;
  `bootstrap/app.php` trusts all proxies. Model URL accessors additionally rebuild media
  URLs through `url()` and drop files under 8 KB / missing on disk
  (`App\Support\Media\ResolvesPublicMediaUrl`).
- To move media to S3 later: `MEDIA_DISK=s3` + the `AWS_*` block. No code change.

## Photo content

Every `Project`, `CommunityPhoto`, and `GalleryPhoto` row's real photo (not an
admin-panel upload — sourced from real Facebook exports) was attached by
`php artisan app:backfill-content-images` (`app/Console/Commands/BackfillContentImages.php`),
not by the seeders. It's **idempotent** — only attaches to a row with no media yet in
its collection, so a panel upload always wins and re-running it after adding more
`resources/seed-images/` files only fills newly-added gaps.

- **Source photos**: `docs/assets/facebook-images/` (gitignored — 2,306 real photos,
  pre-sorted into ~172 folders, documented in `ORGANIZATION_LOG.md`, also gitignored).
  **Several folder labels there are wrong or mix unrelated events** — confirmed on ~8
  folders this session (e.g. `hospital-facility-tour` mixes a real ward tour with an
  unrelated portrait; `lord-mayor-desk-portrait` has a market-construction photo tacked
  on the end; `africell-25-event` is mostly a different event entirely). Always open and
  look at a candidate file before using it — never trust the folder name or log
  description alone. `ORGANIZATION_LOG.md` gets corrected in place as more mislabels turn
  up; it isn't exhaustively audited.
- **Committed photos**: `resources/seed-images/{project,community-photo,gallery-photo}/`
  — small, optimized (Imagick, ≤1800px long edge, quality 78, stripped metadata) copies,
  the only ones actually tracked in git. `resources/seed-images/MANIFEST.md` lists every
  one already in use (mechanically generated from the command's own maps) — check it
  before picking more photos, to avoid duplicating one that's already on the site.
- Three `GalleryPhoto` captions (Hungary/Barcelona/Taiwan twinning claims) were **renamed**
  rather than illustrated, because no genuine photo of any of them exists anywhere in the
  source set — don't invent one from an unrelated country's delegation photo.
- **Still needs to run in production once this ships** — deliberately not wired into
  `.forge/deploy.sh`; nobody's decided yet whether it should run on every deploy (like
  `ContentSeeder`) or once by hand.
- **`docs/assets/gallery-video-2026-09-18.mp4`** (gitignored, staged 2026-09-18) — a
  local source clip, still not wired up. Superseded as the primary video path by
  YouTube import (below) — this one would need its own native-upload treatment if it's
  still wanted, since the Gallery's video support is YouTube-only.

## Gallery videos (YouTube)

`GalleryPhoto` holds both photos and YouTube videos (`type` column: `photo`/`video`;
video rows carry `youtube_video_id`, no local media) — see the model's docblock for why
this is one table rather than two. The public Gallery page (`/gallery`) filters by
category as before, plus an independent All/Photos/Videos row that combines with it; a
video tile shows a play-button overlay and opens the same lightbox, which embeds a
`youtube-nocookie.com` iframe instead of an `<img>` (torn down via `x-if`, not just
hidden via `x-show`, so navigating away or closing actually stops playback rather than
leaving it running invisibly).

- **Nothing is auto-published.** An admin picks videos one at a time, from
  **Content → Import YouTube Videos** (`App\Filament\Admin\Pages\ImportYoutubeVideos`) —
  browse/search the configured channel, "Add to Gallery" opens a modal for
  category/caption/published, then it's a normal `GalleryPhoto` row. The channel is
  never scanned or synced automatically.
- **Needs `YOUTUBE_API_KEY`** (`.env`, free from Google Cloud Console — enable "YouTube
  Data API v3", Credentials → Create API key). Without it, the import page shows a setup
  notice instead of erroring — `App\Support\YouTube\YouTubeChannelClient::isConfigured()`
  gates every call. `YOUTUBE_CHANNEL_HANDLE` defaults to `@talibforpresident`
  (`config/services.php`).
- **`YouTubeChannelClient`**: `channelId()`/`uploadsPlaylistId()` resolve the @handle to
  IDs once and cache for 30 days (they never change) — `listVideos()` (1 quota unit,
  the default/no-search browse) reads that uploads playlist page by page;
  `searchVideos()` (**100 quota units** — only fires when the admin actually types a
  search) hits `search.list` scoped to the channel. Free daily quota is 10,000 units, so
  favour browsing over searching if that ever matters.
- **`youtube_video_id` is unique** on `gallery_photos` — re-adding an already-imported
  video is blocked both in the UI (`alreadyImported()` shows "Already in the Gallery"
  instead of the add button) and in the action itself (checked again before insert, so a
  duplicate-add can't slip through even if the picker's list is stale).
- **Tests fake the HTTP calls** (`tests/Feature/Admin/ImportYoutubeVideosTest.php`,
  `Http::fake([...])`) — there's no live API traffic in the suite, and nothing here has
  ever been exercised against the real API in this codebase yet. If YouTube ever changes
  a response shape, the fakes won't catch it.

## Gotchas

- **Run `php artisan shield:generate --all --panel=admin` after every new Filament
  resource** — Shield does not auto-generate policies, and non-super-admin roles silently
  can't see the resource until you do.
- **CSP**: the strict nonce policy (`config/csp.php`) is appended to the `web` group only.
  Filament panel routes use their own middleware stack and get no CSP — fine, they are
  auth-gated and same-origin. If a public page needs a new external origin, add it in
  `app/Support/Csp/AppPreset.php`.
- `composer install` runs `filament:upgrade` via `post-autoload-dump`. Don't run it by hand.
- `composer test` clears config first because `phpunit.xml` forces in-memory SQLite.
- `tests/TestCase.php` uses `RefreshDatabase`, so settings migrations seed defaults in every
  test — public pages that read settings work without extra seeding.
- No public auth. There is no `/register`, `/login` (that's `/admin/login`), or user
  dashboard. Don't reintroduce `route('login')` in Blade.
- **A dead `queue:work` worker fails silently — check the "App health" page (System nav
  group) first if something queued (media conversions, analytics' `RecordVisitJob`) seems
  to just never happen.** `App\Providers\HealthServiceProvider` registers
  `QueueCheck::new()` (2026-09) specifically for this; it reads the heartbeat
  `routes/console.php` already schedules every minute (`health:queue-check-heartbeat`) —
  before this check existed, a dead worker had no visible symptom anywhere at all.
- **Admin panel favicon**: `AdminPanelProvider::favicon()` only ever renders one
  `<link rel="icon">` (`favicon.ico`); the render hook at `filament.admin.favicon-links`
  adds the same `favicon.svg` + `apple-touch-icon.png` tags the public layout uses, so
  browsers (which prefer an SVG icon when both are present) show an identical tab icon in
  both places instead of a crisper one only on the public site. Keep both lists in sync if
  the favicon ever changes.
- **`composer run dev` (server + queue + pail + Vite via `concurrently --kill-others`) can
  crash entirely — server included — if Vite's file watcher hits the OS's file-watcher
  limit (`ENOSPC`).** This happened when a background agent's isolated git worktree (each
  one under `.claude/worktrees/`, holding a full second `vendor/` install) sat inside the
  project tree — Vite recursed into it, blew the watch limit, died, and `--kill-others`
  took the other three processes down with it, which just looks like "the dev server keeps
  crashing." `vite.config.js` now excludes `.claude/**` and `vendor/**` from the watcher,
  and `.claude/worktrees` is gitignored — if this recurs, check `git worktree list` for
  something living inside the repo tree before assuming it's a code bug.

## Admin login

`AdminUserSeeder` runs on every deploy but creates the account only on the **first** one
(it no-ops once any user has the `admin`/`super-admin` role — a panel password change is
never clobbered). Defaults: `admin@talibahmedbensouda.com` / `password` — override with
`ADMIN_EMAIL` / `ADMIN_PASSWORD` / `ADMIN_NAME` in the Forge env. Change the password from
`/admin/profile` after the first sign-in.

## Mail

`resend/resend-laravel` is wired (`config/resend.php`, `config/services.php`, `config/mail.php`
`resend` mailer). Locally `MAIL_MAILER=log` → mail lands in `storage/logs`. To go live: set
`MAIL_MAILER=resend` + `RESEND_API_KEY` in the Forge env, verify `talibahmedbensouda.com` in
Resend, and point a Resend webhook at `/resend/webhook` with `RESEND_WEBHOOK_SECRET` set.
`App\Listeners\LogResendDeliveryIssue` (registered in `AppServiceProvider`) logs bounces /
complaints / failures. Actual send paths: Filament password reset + spatie backup/health/
failed-job ops notifications. The contact + event-registration forms only write to the DB
inbox — they don't email anyone.

## Performance

Baseline (2026-09-10, prod, Lighthouse mobile): **25/100** — TTFB ~2 s, LCP 10.4 s,
TBT 4.7 s. Optimisation log:

| # | Change | Where | Status |
|---|--------|-------|--------|
| 1 | Self-host Inter + Montserrat (latin woff2) — kill the render-blocking Google Fonts `<link>`, preload the two files, drop the font origins from CSP | `public/fonts/`, `shared/base/_fonts.scss`, `layouts/app.blade.php`, `AppPreset.php` | ✅ done |
| 2 | Lazy-load hero slides 2–6 (only the active one gets `background-image`); `<link rel=preload as=image fetchpriority=high>` for slide 1 | `welcome.blade.php` | ✅ done |
| 3 | Homepage feature video `preload="none"` (was `metadata`) | `welcome.blade.php` | ✅ done |
| 4 | Drop `backdrop-filter: blur()` on the sticky header below `lg` (repaints every scroll frame); near-opaque `--header-bg-solid` instead | `_navbar.scss`, `_themes.scss` | ✅ done |
| 5 | nginx: long cache headers for `/build/*` (1y immutable) + images/fonts/mp4 (30d) | Forge → site → nginx config | ✅ done |
| 6 | Forge deploy script rewritten for the zero-downtime macro format; runs `artisan migrate` / `optimize` / the seeders — this is why TTFB was ~2 s and content was missing | `.forge/deploy.sh` + Forge UI | ✅ done |
| 7 | Responsive hero-image variants — 768w `-sm.webp` served to phones (`≤700px`), full ≤1600w above; media-scoped `<link rel=preload>` mirrors the JS pick so nothing double-loads | `public/images/hero-*`, `HeroSlidesSeeder`, `HomePageSettings::heroSlides`, `welcome.blade.php` | ✅ done |
| 8 | Route-split CSS — `core.scss` (chrome + shared, ~4.5 KB gzip, everywhere) + one `pages-entry/<route>.scss` per page, loaded via the `styles` prop on `<x-app-layout>`. `/` went ~15 → ~7.4 KB gzip | `vite.config.js`, `core.scss`, `pages-entry/`, `AppLayout` | ✅ done |
| 9 | `spatie/laravel-responsecache` — full-page cache for the public site (see **Caching** below) | `CachePublicPages`, `bootstrap/app.php`, `AppServiceProvider` | ✅ done |
| 10 | Hero auto-advance: first change delayed to ~10 s, interval 7 s (was 5 s), skipped entirely under `prefers-reduced-motion` | `welcome.blade.php` | ✅ done |

Progress: baseline 25 → after #1–4 (local, unthrottled) 42 → after #5–7,9,10 (prod, Lighthouse mobile) **~64 median** (51–75; TTFB and hero-rotation variance), TBT ~140–690 ms, LCP 3.7 s. All ten items done.

## Caching

The public marketing site is served from a **full-page response cache**
(`spatie/laravel-responsecache`). It runs against its **own isolated `responsecache`
cache store** (`config/cache.php`, a dedicated `storage/framework/cache/responsecache`
dir) — never the default store — because `ResponseCache::clear()` fires on every
content edit and, without isolation, would wipe the permission cache and anything
else sharing the store, which surfaces in the panel as "Error while loading page".
`App\Support\ResponseCache\CachePublicPages` is the profile: it caches anonymous
GET requests to the marketing pages and
skips `/admin` + Livewire, the two form pages (`/contact`, `/events/register` —
a cached page would freeze the honeypot's encrypted timestamp and hide flash
messages), the health and sitemap endpoints, and anything requested by a
signed-in user.

- **Middleware order matters.** `CacheResponse` is appended to the `web` group
  *before* `AddCspHeaders` (`bootstrap/app.php`), so a cache hit returns before
  the CSP middleware runs and the nonce baked into the cached HTML is replayed
  with its matching `Content-Security-Policy` header. `CsrfTokenReplacer`
  (registered by default) swaps the per-session token on every serve.
- **Invalidation** is automatic: `AppServiceProvider::flushResponseCacheOnContentChange()`
  calls `ResponseCache::clear()` (best-effort — wrapped so a cache hiccup never fails
  the save) on any content-model `saved`/`deleted`, any `SettingsSaved`, and media
  add/clear events. `.forge/deploy.sh` also runs `responsecache:clear` so template
  changes ship immediately.
- **Tests**: disabled globally via `RESPONSE_CACHE_ENABLED=false` in `phpunit.xml`;
  `tests/Feature/ResponseCacheTest.php` re-enables it against an `array` store.
- **Toggle/inspect**: `RESPONSE_CACHE_ENABLED`, `RESPONSE_CACHE_LIFETIME` (default
  86400 s); `php artisan responsecache:clear`. Debug headers (`X-Cache-Status`)
  show when `APP_DEBUG` is on.

## Analytics

**`fomvasss/laravel-visits`** — self-hosted, first-party analytics (replaced the
Plausible-compatible cookieless-script setup in 2026-09; nothing under that name
remains — no `PLAUSIBLE_*` env, no `partials/analytics.blade.php`, no
`services.analytics` config). Tracking is entirely server-side (no client
script needed for basic page views); dashboard at `/analytics` (the package's
own default is `/visits` — renamed via `config('visits.dashboard.path')` /
`VISITS_DASHBOARD_PATH`, and `visits.whoami.path` to match, `/analytics/whoami`).

- **Consent-gated, on purpose.** The cookie-consent banner
  (`layouts/app.blade.php`) already had an "analytics" toggle with nothing
  reading it — Plausible was cookieless, so it never needed one. This package
  sets a durable identity cookie, so it does. `config('visits.consent')` is
  `require_consent = true` with `App\Support\Analytics\CookieConsentResolver`
  as the resolver, which checks a plain `tb_analytics_consent` cookie
  (`1`/`0`) the banner's `save()` sets via `document.cookie` alongside its
  existing `localStorage` write — `TrackVisit` only tracks once that cookie
  says yes. **That cookie must stay in `EncryptCookies`'s `except` list**
  (`bootstrap/app.php`) — it's plain JS-set, not Laravel-encrypted, so without
  the exception `EncryptCookies` treats it as tampered and silently nulls it
  on every request, and consent-based tracking stops working entirely with no
  visible error.
- **Middleware order fights the response cache — read this before touching
  either.** `config/visits.auto_track` is `false` and `'track-visits'` is
  attached to the `web` group by hand in `bootstrap/app.php`, positioned
  *before* `CacheResponse`. Left on `auto_track = true` (the package
  default), the package's own service provider *always* appends itself last
  (deliberately, per its own doc comment — "the last word"), which lands it
  *after* `CacheResponse`. Since `CacheResponse` returns immediately on a
  cache hit without calling further middleware, that would mean only the
  first (uncached) visit to any given page ever gets tracked — everyone
  after that, until the 24h cache entry expires, silently isn't. `TrackVisit`
  itself still respects `exclude_paths` regardless of how it's attached (that
  check lives inside the middleware, not the auto-registration), so this
  reordering doesn't change what's excluded.
- **The dashboard has no auth upstream — do not remove the gate.**
  `config('visits.dashboard.middleware')` includes
  `App\Http\Middleware\EnsureCanViewAnalyticsDashboard` (same admin/super-admin
  role check as the Filament panel — this site has no other auth system).
  Without it, `/analytics/sessions` publicly lists every visitor's IP,
  approximate location and device.
- **The dashboard also can't run under this site's CSP.** It's a third-party,
  pre-built UI (CDN Tailwind runtime, unpkg for Leaflet, jsdelivr for
  Chart.js, inline scripts with no nonce) — incompatible with the strict
  nonce policy the rest of the site runs under. Since it's registered inside
  the `web` group (unlike `/admin`, which is a separate Filament panel stack
  entirely outside `web` and so never sees this CSP at all),
  `App\Http\Middleware\ScopeCspForAnalyticsDashboard` (appended *after*
  `AddCspHeaders`) sets its own scoped-down policy for `/analytics/*` before
  `AddCspHeaders` gets a chance to overwrite it with the site's default —
  see the class docblock for exactly how that ordering trick works.
- **Pruning is scheduled, aggregation/session-closing aren't (by us).**
  `visits:close-stale-sessions` and `visits:aggregate` self-register on a
  fixed schedule (`config('visits.schedule.enabled')`, on by default — no
  `routes/console.php` entry needed). `visits:prune` is deliberately never
  auto-scheduled by the package itself; `routes/console.php` schedules it
  weekly, respecting `visits.retention_days` (90 by default).
- **Geo lookups call an external HTTP API per (uncached) IP by default**
  (`stevebauman/location`'s default driver) — fine for this site's traffic
  volume, but means Plausible's "cookieless, calls nobody" property is gone.
  Switch to the local MaxMind/GeoLite2 driver (see the package's README) if
  that becomes a concern; not done here since it needs a free MaxMind account
  and a scheduled `location:update`.

## SEO / sitemap

`App\Http\Controllers\SitemapController` (`GET /sitemap.xml`) generates the Google-facing
sitemap on every request via `spatie/laravel-sitemap` — static pages plus every `Event`
(`route('events.show', $event)`), so a new seeded/panel-created event is picked up
automatically with no manual sitemap edit. `public/robots.txt` already points crawlers at
it (`Sitemap: https://talibahmedbensouda.com/sitemap.xml`) — that's the URL to paste into
Google Search Console, there's no static file to generate or upload. There's a separate
**human-facing** `/sitemap` page (`resources/views/sitemap-page.blade.php`, linked from the
footer) that also lists every event dynamically — update both files together if a new
top-level route is added, since neither derives from the other.

## Campaign strategy review (2026-09)

`docs/tmg-website-review-notes.md` reviews `docs/TMG Gambia - Website Review - September
2026.pdf` (gitignored, confidential — a campaign consultancy's website-restructure memo).
Verdict: keep the current record-of-delivery information architecture, don't adopt the
memo's proposed page structure or attack-messaging copy; a few UX ideas from it (event
"get directions" links, a single supporter database, brand-colour consistency) are noted
as future additions, and specific policy-position copy / donation flow / entry pop-up are
explicitly flagged as needing the campaign's sign-off before any of them get built.

## Release

`main` is the deploy target; Forge auto-deploys on push. Open a PR into `main`, merge,
Forge runs `.forge/deploy.sh`.
