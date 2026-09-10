<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Csp\AddCspHeaders;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\ResponseCache\Middlewares\CacheResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Forge/Nginx terminates TLS and proxies plain HTTP to PHP-FPM. Trust the
        // forwarded headers so signed URLs, HTTPS detection, and Livewire upload
        // finalisation see the real scheme.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // The strict, nonce-based CSP is appended to the `web` group, which
        // covers the public site. Filament's panel routes run on their own
        // middleware stack (see AdminPanelProvider) and are deliberately left
        // out of this policy — they are auth-gated and serve only same-origin
        // assets, and Filament's inline styles/scripts are incompatible with
        // the site's nonce policy.
        // Order matters. CacheResponse sits *outside* AddCspHeaders: on a cache
        // hit it returns before AddCspHeaders runs, so the CSP nonce baked into
        // the cached HTML is replayed with its matching header instead of a
        // fresh, mismatched one. On a miss the response is stored with both in
        // sync. See App\Support\ResponseCache\CachePublicPages for what's cached.
        $middleware->web(append: [
            ProtectAgainstSpam::class,
            CacheResponse::class,
            AddCspHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
