<x-admin-layout>
    <x-slot name="header">
        <h1>Admin dashboard</h1>
    </x-slot>

    <div class="stat-grid">
        <div class="stat">
            <div class="stat__label">Users</div>
            <div class="stat__value">{{ $userCount }}</div>
        </div>
    </div>

    <div class="card">
        <h2 class="card__title">Recent activity</h2>
        <p>This is your blank admin canvas. Add tables, charts, and forms here using the SCSS classes in <code>resources/sass/admin/</code>.</p>
    </div>
</x-admin-layout>
