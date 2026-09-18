<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// spatie/laravel-backup
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');

// spatie/laravel-health
Schedule::command('health:schedule-check-heartbeat')->everyMinute();
Schedule::command('health:queue-check-heartbeat')->everyMinute();

// spatie/laravel-activitylog (clean records older than 365 days, configurable in config/activitylog.php)
Schedule::command('activitylog:clean')->daily()->at('00:30');

// fomvasss/laravel-visits — close-stale-sessions/aggregate are auto-scheduled by the
// package itself (visits.schedule.enabled). Pruning is deliberately not, per its own
// docs — deleting rows should always be an explicit decision by the host app.
Schedule::command('visits:prune')->weekly()->when(fn () => config('visits.retention_days') > 0);
