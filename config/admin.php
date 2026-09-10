<?php

return [

    /*
     * Seeded administrator account (see Database\Seeders\AdminUserSeeder).
     * Change ADMIN_PASSWORD before deploying to any shared environment.
     */
    'name' => env('ADMIN_NAME', 'Site Admin'),
    'email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'password' => env('ADMIN_PASSWORD', 'password'),

    /*
     * Address that receives operational notifications (backups, failed jobs,
     * failing health checks).
     */
    'notification_email' => env('ADMIN_NOTIFICATION_EMAIL', env('ADMIN_EMAIL', 'admin@example.com')),

];
