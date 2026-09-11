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
  `Tables/<Name>Table.php` + `Pages/*`). Content models: `Event`, `GalleryPhoto`,
  `Project` (`summary` for the home card, full `description` + `metrics` for People's Mayor),
  `Testimonial`, `CommunityPhoto` (`group`: `municipality` = home + People's Mayor,
  `community-support` = Giving Back), `GivingProgramme`, plus the `ContactMessage` /
  `EventRegistration` inboxes. Seed-managed rows carry a stable `key` (or `slug`).
- **`Event` schedule**: the form only asks for one `event_date` + `start_time` / `end_time`
  (defaults 09:00–17:00). `App\Filament\Admin\Resources\Events\Concerns\HandlesEventSchedule`
  (used by `CreateEvent` + `EditEvent`) assembles those into the seven columns the model
  and the public pages actually read — `date_day`/`date_month`/`date_year` (display),
  `js_day`/`js_month` (0-indexed, feed the public calendar grid) and `ics_start`/`ics_end`
  (UTC `YYYYMMDDThhmmssZ`, the calendar export — The Gambia is UTC year-round, so no tz
  math). Events always list newest-first by `ics_start`; there's no `sort_order` for them.
  `badge` (the type pill) is a free label managed under `EventsPageSettings::event_types`,
  not a hardcoded enum. `full_description` is a `RichEditor` (HTML); a model mutator wraps
  any legacy plain text in `<p>` on write. Every event has a flyer image
  (`Event::flyerImageUrl()`) — the real upload, or `App\Support\EventFlyerPlaceholder`
  generates an on-brand SVG on the fly from the title/type/date when none is set.
- **Content seeding:** `ContentSeeder` (projects, events, gallery, community photos,
  testimonials, giving-back programmes + `HeroSlidesSeeder`) runs on every deploy via
  `.forge/deploy.sh`. All of them `firstOrCreate` on the key — a missing row is created,
  an existing one is **never** touched, so panel edits survive re-deploys. `HeroSlidesSeeder`
  copies `public/images/hero-*.webp` onto the media disk and fills `home_page.hero_slides`
  so the slider is editable in the panel; it no-ops once slides are configured.
  `docs/Talib Legacy - The People's Mayor.md` is the source content brief (KMC records +
  public reporting) — the seeders are a curated summary of it, not a transcription. When
  adding seed content, check it against that doc first; a running list of what it covers
  that the site doesn't yet lives in this session's chat history (parks/stadium and
  international partnerships were added; still open: flood-prevention specifics beyond the
  Disaster Mitigation project, deeper governance detail, and a few minor youth-fund items).
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
  Person + WebSite). Layout also `@include`s `partials/analytics` (inert until
  `PLAUSIBLE_DOMAIN` is set).
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

## Release

`main` is the deploy target; Forge auto-deploys on push. Open a PR into `main`, merge,
Forge runs `.forge/deploy.sh`.
