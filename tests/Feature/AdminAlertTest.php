<?php

use App\Support\Notifications\AdminAlert;

it('does not throw when the admin/super-admin roles have not been seeded yet', function (): void {
    // No RolesAndPermissionsSeeder here, on purpose — this is the state of a
    // brand-new environment before the first deploy's seeders have run, and
    // a public form's controller must not 500 in that window.
    AdminAlert::send(
        title: 'Test',
        body: 'Test',
        icon: 'heroicon-o-inbox-arrow-down',
        url: '/admin',
    );

    expect(true)->toBeTrue();
});
