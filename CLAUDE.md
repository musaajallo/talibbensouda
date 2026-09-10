# talibbensouda — working notes

Laravel 13 · PHP 8.4 · Filament 5 admin · SCSS + Alpine public site (no Tailwind on the
front end). Public content is a mix of **spatie/laravel-settings** (page copy, edited via
Filament `SettingsPage`s) and **Eloquent models with Filament resources** (repeating content
with images). All images go through **spatie/laravel-medialibrary** on the local `public`
disk. Modelled on the sibling `umc` project.

## Layout of the admin

- Panel provider: `app/Providers/Filament/AdminPanelProvider.php`, mounted at `/admin`.
- Resources: `app/Filament/Admin/Resources/<Name>/…` (Resource + `Schemas/<Name>Form.php` +
  `Tables/<Name>Table.php` + `Pages/*`).
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

## Release

`main` is the deploy target; Forge auto-deploys on push. Open a PR into `main`, merge,
Forge runs `.forge/deploy.sh`.
