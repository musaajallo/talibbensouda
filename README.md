# Talib Bensouda — Official Website

The official campaign and public-facing website for Talib Bensouda, Mayor and party leader of The Gambia. Built with Laravel 13.

## Features

- Public pages: home, about, gallery, giving back, events, contact, privacy & cookie policy, sitemap
- Event management with public registration and `.ics` calendar download
- Contact form with party join and volunteer sign-up flows
- Admin panel for contract and contract template management
- Role-based access control (Spatie Permissions)
- Media library (Spatie Media Library)
- PDF generation for contracts (Spatie PDF)
- RSS feed and XML sitemap
- Application health monitoring (Spatie Health + Laravel Pulse)
- Response caching, honeypot spam protection, CSP headers
- Automated backups and schedule monitoring

## Requirements

- PHP 8.3+
- Node.js & npm
- A supported database (SQLite, MySQL, PostgreSQL)

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
composer run ide-helper  # Regenerate IDE helper files
```

## Environment

Copy `.env.example` to `.env` and configure your database, mail (Resend), and any third-party service keys before running setup.
