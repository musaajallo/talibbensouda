# Talib Bensouda — Official Website

The official campaign and public-facing website for Talib Bensouda, Mayor and party leader of The Gambia. Built with Laravel 13 and a Filament 5 admin panel.

## Features

- Public pages: home, about, The People's Mayor, gallery, giving back, events, contact, privacy & cookie policy, sitemap
- **Filament CMS at `/admin`**: events & milestones, photo gallery, editable header/footer/social/contact copy (spatie/laravel-settings), contact-message and event-registration inboxes, admin-user and role management
- Event management with public registration and `.ics` calendar download
- Image uploads via Spatie Media Library (local `public` disk) + image optimisation
- Role-based access control (spatie/laravel-permission + Filament Shield)
- RSS feed and XML sitemap
- Application health monitoring (Spatie Health + Laravel Pulse)
- Response caching, honeypot spam protection, CSP headers
- Automated backups and schedule monitoring

See [`CLAUDE.md`](CLAUDE.md) for the CMS architecture and common recipes.

## Requirements

- PHP 8.4+
- Node.js & npm
- A supported database (MySQL or PostgreSQL; SQLite for local/CI)
- For production image optimisation: `jpegoptim optipng pngquant gifsicle webp`

## Setup

```bash
composer run setup
```

This installs dependencies, creates `.env`, generates an app key, runs migrations and seeders, links storage, and builds frontend assets.

To start the development server (Laravel + queue + logs + Vite):

```bash
composer run dev
```

## Useful commands

```bash
composer run test      # Run the test suite
composer run pest      # Run Pest directly
composer run analyse   # PHPStan static analysis
composer run lint      # Pint (check only) — composer run lint:fix to apply
composer run ide-helper  # Regenerate IDE helper files
```

After adding a Filament resource, regenerate Shield permissions:

```bash
php artisan shield:generate --all --panel=admin
```

## Environment

Copy `.env.example` to `.env` and configure your database, mail (Resend), and any third-party service keys before running setup.
