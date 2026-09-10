#!/usr/bin/env bash
# =============================================================================
# Forge deploy script — paste into the site's Deploy Script (Deployments tab).
# Tracked here so the deploy command stays under version control; update the
# Forge UI alongside this file so the two never drift.
#
# This is Forge's ZERO-DOWNTIME format. The $CREATE_RELEASE / $ACTIVATE_RELEASE
# / $RESTART_QUEUES macros are expanded by Forge:
#   $CREATE_RELEASE()   checks out the deployed branch into a fresh release dir
#                       and links the shared paths (.env, storage/, etc.)
#   $ACTIVATE_RELEASE() swaps the `current` symlink and reloads PHP-FPM
#   $RESTART_QUEUES()   restarts the queue workers against the new code
#
# Forge injects $FORGE_COMPOSER, $FORGE_PHP, $FORGE_PHP_FPM and
# $FORGE_RELEASE_DIRECTORY automatically. No `git pull` — $CREATE_RELEASE does it.
# =============================================================================

$CREATE_RELEASE()

cd $FORGE_RELEASE_DIRECTORY

# --- PHP dependencies (production) -------------------------------------------
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress

# --- Frontend assets --------------------------------------------------------
npm ci || npm install
npm run build

# --- Storage symlink ------------------------------------------------------------
# storage/ is a Forge shared path, so this points the release at the persistent
# storage/app/public across deploys.
$FORGE_PHP artisan storage:link

# --- Filament runtime assets --------------------------------------------------
# Re-published on every deploy so a Filament version bump ships its JS/CSS.
$FORGE_PHP artisan filament:assets

# --- Migrations -------------------------------------------------------------
# Runs both database/migrations and database/settings.
$FORGE_PHP artisan migrate --force --graceful

# --- Roles, permissions, the admin account & site content ------------------
# shield:generate refreshes permissions/policies for any new resources;
# RolesAndPermissionsSeeder re-syncs the admin roles to the full set;
# AdminUserSeeder creates the admin login on the first deploy (idempotent —
# it only re-asserts roles once an admin exists, so a panel password change
# is never overwritten). Set ADMIN_EMAIL / ADMIN_PASSWORD in the Forge env.
# ContentSeeder fills in projects, events, gallery, community photos,
# testimonials, giving-back programmes and the hero slider (via HeroSlidesSeeder)
# — create-if-missing only, so rows edited in the panel are never overwritten.
$FORGE_PHP artisan shield:generate --all --panel=admin --no-interaction || true
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\RolesAndPermissionsSeeder --force
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
$FORGE_PHP artisan db:seed --class=Database\\Seeders\\ContentSeeder --force

# --- Cache rebuild --------------------------------------------------------
$FORGE_PHP artisan optimize:clear
$FORGE_PHP artisan optimize
$FORGE_PHP artisan filament:cache-components || true

$ACTIVATE_RELEASE()

$RESTART_QUEUES()
