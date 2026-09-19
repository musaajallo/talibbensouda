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
  photos, testimonials, giving-back programmes + `HeroSlidesSeeder`) is **run by hand**
  (`php artisan db:seed --class=Database\\Seeders\\ContentSeeder --force`) — deliberately *not*
  in the deploy script (see Release), because it re-creates any seeded row that's missing,
  which would resurrect content deleted in the panel. All of them `firstOrCreate` on the key —
  a missing row is created, an existing one is **never** touched, so panel edits survive re-runs. `HeroSlidesSeeder`
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
  `public/storage` symlink. the deploy script rebuilds that symlink each deploy.
- **Optimiser binaries are required** or `spatie/laravel-image-optimizer` silently no-ops:
  `sudo apt install jpegoptim optipng pngquant gifsicle webp`.
- Conversions / responsive images run on the **queue** — a `queue:work` worker must be
  running (systemd or Supervisor). The deploy restarts the workers (`$RESTART_QUEUES()`); if yours run under your own systemd/Supervisor rather than Forge's Queue tab, that macro won't reach them — use `artisan queue:restart` instead.
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
  the deploy script; run it by hand, once (it's idempotent — it only attaches to rows with no
  media yet).
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
- **A YouTube entry is not edited like a photo.** It has no file of its own (the video
  lives on YouTube), so `GalleryPhotoForm` / `GalleryPhotoInfolist` swap the required photo
  uploader for a **Video** section (`filament/admin/resources/gallery-photos/youtube-video.blade.php`
  — non-autoplaying embed, the video ID, an "Open on YouTube" link) whenever
  `$record->isVideo()`; caption / category / published / sort order stay editable. Without
  that, opening a video row showed an empty uploader and could not be saved (photo
  `required`). The table shows `thumbnailUrl()` plus a Photo/YouTube type badge for the
  same reason. Photo rows, and the Create page, keep the uploader. Direct video *upload*
  is still not supported — Gallery video is YouTube-only.
- **Home page video slider** (`<x-video-slider>`, `resources/views/components/video-slider.blade.php`,
  directly below "Across the Municipality" in `welcome.blade.php`) shows
  **YouTube videos from the Gallery**, via `GalleryPhoto::forHomeSlider()` — same rows and
  order (`sort_order`, then `id`; reorder them in the Gallery list), capped at 12. Nothing to
  curate separately: publish/unpublish/reorder in the Gallery and the strip follows (the
  response cache is already flushed on any `GalleryPhoto` save). **"Feature on home page"**
  (`gallery_photos.featured_on_home`, a toggle beside *Published* in the import modal and on
  a video's edit form; videos only, "Featured" badge in the Gallery list): once **any
  published** video is featured, the section shows *only* those; with none featured it shows
  every published video. A featured-but-unpublished video deliberately doesn't count — it
  can't show, and letting it flip the section to "featured only" would leave it empty. The
  public Gallery is unaffected. The whole section hides
  while there are none. It's a native scroll-snap strip (swipe / arrows / Tab), **no
  autoplay**, with the arrows in gutters either side of the strip, vertically centred on the
  *thumbnails* (a `cqw` calc — `top` can't reference a width otherwise; overlaid on the
  thumbnails on phones) and **hidden when everything already fits**, and with the slides
  **centred** when there are too few to fill the row (auto margins on the first/last slide,
  not `justify-content: center`, so a full strip still scrolls to both ends), and a click opens the shared `.lightbox` with the `youtube-nocookie` embed
  (`x-if`, so closing tears the iframe down). Two gotchas: keep `data-reveal` **off** the
  component's wrapper — a transformed ancestor breaks the lightbox's `position: fixed` —
  and `.lightbox__video` now lives in `components/_lightbox.scss` (it's shared between the
  Gallery and Home). Section copy (`videos_*`) is in `HomePageSettings` / *Page content →
  Home page*. Its "Browse All Videos" button links to `/gallery?media=Videos`, which the
  Gallery reads on load (`?media=Photos` works too) to pre-select the media-type filter.
- **The home video slider auto-advances** (`x-data` in `video-slider.blade.php`): one slide
  every 4 s (`interval`), in order left to right, looping back to the start. Never moves
  under the visitor: it's *held* — and the countdown restarts — while a **mouse** hovers
  (`pointerenter` with a `pointerType === 'mouse'` check: touch fires an emulated
  `mouseenter` with no `mouseleave`, which left it "hovered" and stopped for good after one
  tap on a phone), a slide has **keyboard** focus (`:focus-visible` only — a mouse click's
  focus must not stick), a finger is on it or was 1.5 s ago (also covers swipe momentum),
  the lightbox is open, the section is off-screen, or the tab is hidden. Off entirely when
  everything already fits, and for `prefers-reduced-motion`. A **pause/play button** beside
  "Browse All Videos" is the non-hover way to stop it (WCAG 2.2.2). The interval isn't a
  panel setting; change `interval` in the component. Tested with a headless-browser script
  (not in the suite) — its synthetic touch can't reproduce a real phone cancelling a swipe.
- **Add by link** (`AddYoutubeVideosByLinkAction`, a header button on both the YouTube
  Videos page and the Gallery list): paste YouTube links — one per line, or space/comma
  separated, up to 20 — for videos that are **not on the campaign's channel** and so never
  appear in the channel browser. Any link shape works (`watch?v=`, `youtu.be`, Shorts,
  embed, live, or a bare ID — `App\Support\YouTube\YouTubeUrl`). Category, Published and
  **Feature on home page** apply to the whole paste. Each video is checked and titled through
  YouTube's public **oEmbed** endpoint (`YouTubeOEmbed`): no API key, no quota, and it only
  answers for videos that are public *and* embeddable, so it doubles as validation — a
  private/removed/non-embeddable link is skipped, not turned into a broken tile. **A made-up
  ID gets a 400 from oEmbed, not a 404** (found against the live endpoint); 5xx/timeouts are
  reported as "couldn't reach YouTube, try again", never as "no such video". A video already in
  the Gallery is never duplicated, but if the paste asked to feature, the existing row is
  featured. Captions are the YouTube titles (editable afterwards); the videos belong to
  whoever's channel they're on.
- **Admin Gallery list filters:** *Media type* (Photos / YouTube videos — `SelectFilter` on the
  `type` column) and *Published*, in the funnel menu. Filament defers filters until **Apply
  filters** is clicked. They combine with each other and with the category tabs; the *Type*
  column badge shows each row's kind, and *Home page* marks featured videos.
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

`AdminUserSeeder` is **not** run by the deploy (see Release) — run it by hand on a fresh environment. It creates the account only on the **first** run
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
| 6 | Forge deploy script rewritten for the zero-downtime macro format; runs `artisan migrate` / `optimize` (the seeders are run by hand — see Release) | `.forge/deploy.sh` + Forge UI | ✅ done |
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

- **Cache keys carry a per-deploy fingerprint** (`CachePublicPages::useCacheNameSuffix()` →
  hash of the release directory + `public/build/manifest.json`). Incident (2026-09-19):
  production served `/` with a stylesheet 404 — the cached home page named
  `home-<old hash>.css`, a file the current build no longer had — so it was unstyled for
  anyone whose browser hadn't already cached the old file, typically a phone. Cause: **the
  deploy script never cleared the response cache** (see *Release*), so a page cached before a deploy kept being served for
  up to 24 h with the old build's asset names; only `/` showed it because it was the only
  page whose CSS hash changed. Fix: the fingerprint means a release only ever reads its own
  entries — the release directory (unique per zero-downtime release) makes even a
  Blade/PHP-only deploy start clean, the manifest hash covers a same-directory rebuild — so
  no `responsecache:clear` is needed on deploy. If a page ever looks unstyled on production:
  fetch it and check its `build/assets/*.css` links return 200, and compare with
  `/build/manifest.json`.
- **Middleware order matters.** `CacheResponse` is appended to the `web` group
  *before* `AddCspHeaders` (`bootstrap/app.php`), so a cache hit returns before
  the CSP middleware runs and the nonce baked into the cached HTML is replayed
  with its matching `Content-Security-Policy` header. `CsrfTokenReplacer`
  (registered by default) swaps the per-session token on every serve.
- **Invalidation** is automatic: `AppServiceProvider::flushResponseCacheOnContentChange()`
  calls `ResponseCache::clear()` (best-effort — wrapped so a cache hiccup never fails
  the save) on any content-model `saved`/`deleted`, any `SettingsSaved`, and media
  add/clear events. The deploy script doesn't clear it — the per-deploy fingerprint
  above is what makes template changes ship immediately.
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
memo's proposed page structure or attack-messaging copy. The memo's entry pop-up (below)
has since been built, on explicit sign-off; specific policy-position copy and a donation
flow remain explicitly out of scope until the campaign signs off on those separately.

## Sign-up pop-up

A site-wide pop-up (`resources/views/components/campaign-signup-popup.blade.php`,
included once in `layouts/app.blade.php`) collects **name + phone + optional
location/"Geography"** and writes to its own `CampaignSignup` inbox
(`Submissions → Pop-up sign-ups` in the panel) — a separate model from `ContactMessage`
on purpose, since it collects no email and doesn't fit that model's required-email shape.

- **Two deliberate departures from the TMG memo's original spec**, both on privacy
  grounds: no **Voter Number** field (collecting voter-roll data publicly was judged too
  sensitive to build without a much more deliberate, separate decision — see the review
  doc), and the "send me updates" consent checkbox is **unchecked by default** rather
  than the memo's suggested pre-ticked box — a pre-ticked box is a dark pattern and
  directly at odds with the explicit-consent approach the whole rest of the site
  (cookie banner, analytics gate) already uses.
- **Shows once per visitor at a time, and a dismissal is "not now", not "never".** It only
  appears after the visitor has **actually used the site** — asking for details before that
  is a bad first impression. `SiteChromeSettings::$signup_popup_delay_seconds` (default
  **120**, editable under *System → Header & footer*; 0 = as soon as the cookie banner is out
  of the way) is measured as *engaged* time, totalled **across pages** in `localStorage`
  (`tb_signup_popup_elapsed`) — a per-page timer would restart on every navigation and
  almost never fire. A second only counts while the tab is visible and the visitor has
  scrolled / tapped / typed / moved the mouse in the last 30 s, so a background tab isn't
  "browsing". The delay is rendered into the page, so it rides the response cache —
  saving settings already clears that cache.
- **Dismissal snoozes it for `$signup_popup_snooze_days` (default 14; 0 = never again).**
  Closing it (X / backdrop / Escape) stores the *time* in `tb_signup_popup_seen`. Once the
  snooze lapses that flag **and the engagement clock are both wiped**, so the visitor gets a
  full `delaySeconds` of browsing again — leaving the old total in place would make it fire on
  the first page load, which is exactly what the delay exists to prevent. **Signing up is
  final:** a submission sets `tb_signup_popup_done`, which never expires. A bare `'1'` in
  `tb_signup_popup_seen` (what the first version stored) is read as "dismissed now". All of
  this is per-browser `localStorage` — a different device, a private window, or cleared site
  data starts over.
- **Never stacks with the cookie banner.** The banner (`z-index` 9000) sits above the
  pop-up and would cover half the form, so once the delay is up the pop-up still waits
  for the banner to be answered (it listens for the banner's `cookie-consent-saved`
  window event, then opens ~1.5 s later). Re-opens immediately (bypassing the delay and
  the "already seen" check) on a real submission (to show the thank-you state) or on a
  validation error, driven by `session('campaign_signup_success')` /
  `$errors->campaignSignup` respectively.
- **Submit feedback vs. the response cache.** The redirect-back GET after a submission
  carries session flash data, which a cached page can't show. `CachePublicPages::enabled()`
  returns `false` while `campaign_signup_success` or `errors` is in the session — it has to
  be `enabled()`, not `shouldCacheRequest()`, because only `enabled()` gates the cache
  *lookup* (the latter only decides what gets stored). Without it the pop-up re-opened empty
  with no thank-you and no errors.
- **Submittable from any page** (`POST /campaign-signup`, `CampaignSignupController`) —
  a plain form POST that redirects back to wherever the visitor was (`redirect()->back()`),
  since there's no single "signup page" this belongs to. Uses a **named error bag**
  (`validateWithBag('campaignSignup', …)`) and **prefixed field names** (`popup_name`,
  `popup_phone`, …) specifically so a validation failure here can never collide with
  another form's `name`/`phone` fields on the same page (e.g. the contact page).
- **Copy is admin-editable**: `SiteChromeSettings::$signup_popup_*` via *System → Header
  & footer* (it's chrome, not page content — shown everywhere, not tied to one page).
  `signup_popup_enabled` is a full kill switch.
- `App\Support\Notifications\AdminAlert` (shared with the contact/event-registration
  inboxes) now tolerates the admin/super-admin roles not existing yet — building this
  surfaced a latent bug where any of these three forms would 500 on a fresh environment
  before `RolesAndPermissionsSeeder` had ever run (`tests/Feature/AdminAlertTest.php`).

## Release

`main` is the deploy target; Forge auto-deploys on push. Open a PR into `main`, merge,
Forge runs **the Deploy Script stored in Forge** (site → Deployments). `.forge/deploy.sh` is the
tracked copy and was synced from it on 2026-09-19 — **keep the two identical** (Forge doesn't
read the repo file). It is: `$CREATE_RELEASE()`, `composer install`, `npm ci || npm install`,
`npm run build`, `artisan optimize`, `storage:link`, `migrate --force` (which also runs the
`database/settings` migrations), `$ACTIVATE_RELEASE()`, `$RESTART_QUEUES()`.

It **deliberately doesn't** run seeders, `shield:generate`, `filament:assets`,
`responsecache:clear` or `migrate --graceful` (reasons are in the script's header). So:
- new seed content reaches production only when you run the seeder by hand
  (`php artisan db:seed --class=Database\\Seeders\\ContentSeeder --force`);
- after adding a Filament resource, run `shield:generate --all --panel=admin` **and** the
  `RolesAndPermissionsSeeder` on the server, or non-super-admin roles can't see it
  (super-admin bypasses via `Gate::before`) — e.g. *Pop-up sign-ups* needed this;
- the page cache needs no clearing: its keys carry a per-deploy fingerprint.
