<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Auth\Login;
use App\Filament\Admin\Auth\RequestPasswordReset;
use App\Filament\Admin\Auth\ResetPassword;
use App\Filament\Admin\Widgets\ContentOverview;
use App\Filament\Admin\Widgets\LatestContactMessages;
use App\Filament\Admin\Widgets\LatestEventRegistrations;
use App\Filament\Admin\Widgets\QuickActions;
use App\Filament\Admin\Widgets\SubmissionsOverview;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->passwordReset(RequestPasswordReset::class, ResetPassword::class)
            ->profile(isSimple: false)
            ->brandName(config('app.name'))
            ->brandLogo(fn (): View => view('filament.admin.brand'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('favicon.ico'))
            ->colors([
                // Campaign navy — matches $color-navy in the public SCSS tokens.
                'primary' => Color::hex('#0d1b38'),
                'gray' => Color::Stone,
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->topNavigation()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications(fn (): bool => auth()->user()?->hasRole('super-admin') ?? false)
            ->databaseNotificationsPolling('30s')
            ->userMenuItems([
                MenuItem::make()
                    ->label('Visit site')
                    ->url(fn (): string => url('/'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ])
            ->navigationGroups([
                'Content',
                'Page content',
                'Submissions',
                'System',
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                QuickActions::class,
                SubmissionsOverview::class,
                ContentOverview::class,
                LatestContactMessages::class,
                LatestEventRegistrations::class,
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): View => view('filament.admin.visit-site'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): View => view('filament.admin.auth.back-to-home'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_BEFORE,
                fn (): View => view('filament.admin.auth.back-to-login'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_PASSWORD_RESET_RESET_FORM_BEFORE,
                fn (): View => view('filament.admin.auth.back-to-login'),
            )
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('System')
                    ->navigationLabel('Roles & permissions')
                    ->navigationIcon('heroicon-o-shield-check')
                    ->navigationSort(40),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->navigationGroup('System')
                    ->navigationLabel('DB & app backups')
                    ->navigationIcon('heroicon-o-circle-stack')
                    ->navigationSort(20),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->navigationGroup('System')
                    ->navigationLabel('App health')
                    ->navigationIcon('heroicon-o-heart')
                    ->navigationSort(30),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
