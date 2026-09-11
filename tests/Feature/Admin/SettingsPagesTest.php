<?php

use App\Filament\Admin\Pages\ManageAboutPage;
use App\Filament\Admin\Pages\ManageEventsPage;
use App\Filament\Admin\Pages\ManageGeneralSettings;
use App\Filament\Admin\Pages\ManageGivingBackPage;
use App\Filament\Admin\Pages\ManageHomePage;
use App\Filament\Admin\Pages\ManagePeoplesMayorPage;
use App\Filament\Admin\Pages\ManageSiteChrome;
use App\Filament\Admin\Pages\ManageSocialSettings;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);
});

$pages = [
    'General settings' => [ManageGeneralSettings::class, 'admin/manage-general-settings'],
    'Social links' => [ManageSocialSettings::class, 'admin/manage-social-settings'],
    'Header & footer' => [ManageSiteChrome::class, 'admin/manage-site-chrome'],
    'Home page content' => [ManageHomePage::class, 'admin/manage-home-page'],
    'About page content' => [ManageAboutPage::class, 'admin/manage-about-page'],
    "People's Mayor page content" => [ManagePeoplesMayorPage::class, 'admin/manage-peoples-mayor-page'],
    'Giving Back page content' => [ManageGivingBackPage::class, 'admin/manage-giving-back-page'],
    'Events page content' => [ManageEventsPage::class, 'admin/manage-events-page'],
];

it('loads every settings page without error', function (string $class, string $url): void {
    get($url)->assertOk();
    Livewire::test($class)->assertOk();
})->with(collect($pages)->mapWithKeys(fn ($v, $k) => [$k => $v])->all());

it('saves every settings page (form round-trips cleanly)', function (string $class): void {
    Livewire::test($class)
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
})->with(collect($pages)->mapWithKeys(fn ($v, $k) => [$k => [$v[0]]])->all());

it('shows the expected page heading', function (string $title, array $page): void {
    Livewire::test($page[0])->assertSee($title);
})->with(collect($pages)->mapWithKeys(fn ($v, $k) => [$k => [$k, $v]])->all());
