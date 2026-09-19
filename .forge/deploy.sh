#!/usr/bin/env bash
# =============================================================================
# Forge deploy script — the site's Deploy Script (Forge → site → Deployments).
# Tracked here so the deploy command stays under version control; update the
# Forge UI alongside this file so the two never drift. (Last synced by hand
# from the Forge UI on 2026-09-19 — the commands below are exactly what runs.)
#
# This is Forge's ZERO-DOWNTIME format. The $CREATE_RELEASE / $ACTIVATE_RELEASE
# / $RESTART_QUEUES macros are expanded by Forge (so this file is not plain bash):
#   $CREATE_RELEASE()   checks out the deployed branch into a fresh release dir
#                       and links the shared paths (.env, storage/, etc.)
#   $ACTIVATE_RELEASE() swaps the `current` symlink and reloads PHP-FPM
#   $RESTART_QUEUES()   restarts the queue workers against the new code
#
# Forge injects $FORGE_COMPOSER, $FORGE_PHP and $FORGE_RELEASE_DIRECTORY
# automatically. No `git pull` — $CREATE_RELEASE does it.
#
# DELIBERATELY NOT HERE — run these by hand when you actually want them:
#   * Seeders (ContentSeeder, AdminUserSeeder, RolesAndPermissionsSeeder). The
#     content seeders re-create any seeded row that's missing, so on every deploy
#     they'd resurrect content deleted in the panel; AdminUserSeeder would
#     re-create a default-password admin if the last one were ever removed.
#       php artisan db:seed --class=Database\\Seeders\\ContentSeeder --force
#   * shield:generate — after adding a Filament resource:
#       php artisan shield:generate --all --panel=admin
#       php artisan db:seed --class=Database\\Seeders\\RolesAndPermissionsSeeder --force
#   * migrate --graceful — it turns a failed migration into a "successful"
#     deploy; plain --force fails the deploy BEFORE the release goes live.
#   * responsecache:clear — not needed: every response-cache key carries a
#     per-deploy fingerprint (App\Support\ResponseCache\CachePublicPages).
# =============================================================================

$CREATE_RELEASE()

cd $FORGE_RELEASE_DIRECTORY

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

npm ci || npm install
npm run build
$FORGE_PHP artisan optimize
$FORGE_PHP artisan storage:link
# Runs both database/migrations and database/settings.
$FORGE_PHP artisan migrate --force

$ACTIVATE_RELEASE()

$RESTART_QUEUES()
