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
- **Content seeding:** `ContentSeeder` (projects, events, gallery, community photos,
  testimonials, giving-back programmes + `HeroSlidesSeeder`) runs on every deploy via
  `.forge/deploy.sh`. All of them `firstOrCreate` on the key — a missing row is created,
  an existing one is **never** touched, so panel edits survive re-deploys. `HeroSlidesSeeder`
  copies `public/images/hero-*.webp` onto the media disk and fills `home_page.hero_slides`
  so the slider is editable in the panel; it no-ops once slides are configured.
- Page-copy settings: `GeneralSettings`, `SocialSettings`, `SiteChromeSettings`,
  `HomePageSettings`, `PeoplesMayorPageSettings`, `GivingBackPageSettings`, `AboutPageSettings`.
  Multi-paragraph fields are stored blank-line-separated and split with the
  `App\Settings\Concerns\SplitsParagraphs` trait (`$page->paragraphs('bio_body')`).
- Each public view reads its models + settings in a top `@php` block and degrades gracefully
  (a section hides when its collection is empty; images fall back to a placeholder tile;
  decorative SVG icons are cycled by index, not stored).
- Settings pages: `app/Filament/Admin/Pages/Manage*.php` extending `Filament\Pages\SettingsPage`,
  each bound to a class in `app/Settings/`. Nav group **System** for now; page-body settings
  will land under **Page content**.
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

## Release

`main` is the deploy target; Forge auto-deploys on push. Open a PR into `main`, merge,
Forge runs `.forge/deploy.sh`.
