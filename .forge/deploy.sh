#!/usr/bin/env bash
# =============================================================================
# Forge deploy script — paste into the site's "Deploy Script" field.
# Tracked here so the deploy command stays under version control; update the
# Forge UI alongside this file.
#
# Forge injects $FORGE_COMPOSER, $FORGE_PHP, $FORGE_PHP_FPM, $FORGE_SITE_PATH
# and $FORGE_SITE_BRANCH automatically.
# =============================================================================

set -e

cd "$FORGE_SITE_PATH"

git pull origin "$FORGE_SITE_BRANCH"

# --- PHP dependencies (production) ------------------------------------------
$FORGE_COMPOSER install --no-interaction --no-dev --prefer-dist --optimize-autoloader --no-progress

# --- Frontend assets -------------------------------------------------------
npm ci --no-audit --no-fund --prefer-offline
npm run build

# --- Storage symlink -----------------------------------------------------------
# Zero-downtime deploys: $FORGE_SITE_PATH is a release dir under releases/, and
# shared storage lives two levels up. `artisan storage:link` would point at the
# release-local (empty) storage, so build the link manually.
SHARED_STORAGE="$(cd "$FORGE_SITE_PATH/../.." && pwd)/storage/app/public"
mkdir -p "$SHARED_STORAGE"
rm -rf public/storage
ln -s "$SHARED_STORAGE" public/storage

# --- Filament runtime assets ---------------------------------------------------
# Re-published on every deploy so a Filament version bump ships its JS/CSS.
$FORGE_PHP artisan filament:assets

# --- Migrations --------------------------------------------------------------
# Runs both database/migrations and database/settings.
$FORGE_PHP artisan migrate --force --graceful

# --- Roles, permissions, the admin account & site content ------------------
# shield:generate refreshes permissions/policies for any new resources;
# RolesAndPermissionsSeeder re-syncs the admin roles to the full set;
# AdminUserSeeder creates the admin login on the first deploy (idempotent —
# it only re-asserts roles once an admin exists, so a panel password change
# is never overwritten). Set ADMIN_EMAIL / ADMIN_PASSWORD in the Forge env.
# ContentSeeder fills in projects, events, gallery, community photos,
# testimonials, giving-back programmes and the hero slider — create-if-missing
# only, so rows edited in the panel are never overwritten.
$FORGE_PHP artisan shield:generate --all --panel=admin --no-interaction || true
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\RolesAndPermissionsSeeder --force
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\ContentSeeder --force

# --- Cache rebuild ---------------------------------------------------------
$FORGE_PHP artisan optimize:clear
$FORGE_PHP artisan optimize
$FORGE_PHP artisan filament:cache-components || true

# --- Restart workers so they pick up new code -----------------------------
$FORGE_PHP artisan queue:restart

# --- Reload PHP-FPM ------------------------------------------------------
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'
    sudo -S service "$FORGE_PHP_FPM" reload
) 9>/tmp/fpmlock

echo "Deploy finished. Probe: $APP_URL/up"
