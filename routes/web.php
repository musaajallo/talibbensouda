<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\SimpleHealthCheckController;

Route::get('/', fn () => view('welcome'));
Route::get('/about', fn () => view('about'));
Route::get('/peoples-mayor', fn () => view('peoples-mayor'));
Route::get('/gallery', fn () => view('gallery'));
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/register', [EventRegistrationController::class, 'show'])->name('events.register');
Route::post('/events/register', [EventRegistrationController::class, 'store'])->name('events.register.store');

Route::get('/events/ics', function (Request $request) {
    $title = (string) $request->string('title')->limit(200);
    $start = preg_replace('/[^0-9TZ]/', '', (string) $request->query('start', ''));
    $end = preg_replace('/[^0-9TZ]/', '', (string) $request->query('end', ''));
    $loc = (string) $request->string('location')->limit(300);
    $desc = (string) $request->string('description')->limit(1000);
    $uid = md5($title.$start).'@talibahmedbensouda.com';

    $esc = fn (string $s) => str_replace(["\r", "\n", ',', ';'], [' ', ' ', '\\,', '\\;'], $s);
    $escDesc = fn (string $s) => str_replace(["\r", "\n", ',', ';'], [' ', '\\n', '\\,', '\\;'], $s);

    $ics = implode("\r\n", [
        'BEGIN:VCALENDAR', 'VERSION:2.0',
        'PRODID:-//Talib Bensouda//Events//EN',
        'CALSCALE:GREGORIAN', 'METHOD:PUBLISH',
        'BEGIN:VEVENT',
        'UID:'.$uid,
        'DTSTAMP:'.gmdate('Ymd\THis\Z'),
        'DTSTART:'.$start,
        'DTEND:'.$end,
        'SUMMARY:'.$esc($title),
        'LOCATION:'.$esc($loc),
        'DESCRIPTION:'.$escDesc($desc),
        'END:VEVENT', 'END:VCALENDAR',
    ]);

    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $title), '-'));

    return response($ics, 200, [
        'Content-Type' => 'text/calendar; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="'.($slug ?: 'event').'.ics"',
    ]);
})->name('events.ics');

// Show route MUST come after /events/register and /events/ics
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/giving-back', fn () => view('giving-back'));
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/join', [ContactController::class, 'join'])->name('contact.join');
Route::get('/cookies', fn () => view('cookie-policy'))->name('cookies');
Route::get('/privacy', fn () => view('privacy-policy'))->name('privacy');
Route::get('/sitemap', fn () => view('sitemap-page'))->name('sitemap.page');

// Public JSON endpoint for uptime monitors / load balancers.
Route::get('/health-check', SimpleHealthCheckController::class);

Route::get('/sitemap.xml', SitemapController::class);

// Admin authentication and the whole back-office live in the Filament panel at
// /admin (see App\Providers\Filament\AdminPanelProvider). There is no public
// account area — the site has no self-service registration or user dashboard.
