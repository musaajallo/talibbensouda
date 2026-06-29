<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin · {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/admin/admin.scss', 'resources/js/admin.js'])
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-sidebar__brand">{{ config('app.name') }}</div>
            <ul class="admin-sidebar__nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.contracts.index') }}"
                       class="admin-sidebar__link {{ request()->routeIs('admin.contracts.*') ? 'is-active' : '' }}">
                        Contracts
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.contract-templates.index') }}"
                       class="admin-sidebar__link {{ request()->routeIs('admin.contract-templates.*') ? 'is-active' : '' }}">
                        Templates
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.health') }}"
                       class="admin-sidebar__link {{ request()->routeIs('admin.health') ? 'is-active' : '' }}">
                        Health
                    </a>
                </li>
                <li>
                    <a href="/pulse" class="admin-sidebar__link {{ request()->is('pulse*') ? 'is-active' : '' }}">
                        Pulse
                    </a>
                </li>
            </ul>
        </aside>

        <header class="admin-topbar">
            <div>{{ $header ?? '' }}</div>
            <div>
                <span style="margin-right: 12px">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn--secondary">Log out</button>
                </form>
            </div>
        </header>

        <main class="admin-main">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
