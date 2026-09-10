<?php

use App\Models\Event;
use App\Models\GivingProgramme;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use App\Settings\HomePageSettings;
use Database\Seeders\ContentSeeder;
use Database\Seeders\HeroSlidesSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;
use function Pest\Laravel\seed;

it('fills empty content tables on a fresh install', function (): void {
    expect(Project::count())->toBe(0);

    seed(ContentSeeder::class);

    expect(Project::query()->where('published', true)->count())->toBeGreaterThan(0)
        ->and(Event::count())->toBeGreaterThan(0)
        ->and(Testimonial::count())->toBeGreaterThan(0)
        ->and(GivingProgramme::count())->toBeGreaterThan(0);
});

it('never overwrites a row that already exists', function (): void {
    seed(ContentSeeder::class);

    $project = Project::query()->firstWhere('key', 'mbalit');
    $project->update(['title' => 'Renamed in the admin panel', 'published' => false]);

    seed(ContentSeeder::class);

    $project->refresh();
    expect($project->title)->toBe('Renamed in the admin panel')
        ->and($project->published)->toBeFalse();
});

it('re-running the seeder creates no duplicates', function (): void {
    seed(ContentSeeder::class);
    $first = Project::count();

    seed(ContentSeeder::class);

    expect(Project::count())->toBe($first);
});

it('seeds the hero slider as editable rows backed by copied images', function (): void {
    Storage::fake('public');

    seed(HeroSlidesSeeder::class);

    $slides = app(HomePageSettings::class)->hero_slides;

    expect($slides)->toHaveCount(6)
        ->and($slides[0])->toHaveKeys(['image', 'image_sm', 'bg', 'position'])
        ->and($slides[0]['position'])->toBe('center top');

    Storage::disk('public')->assertExists($slides[0]['image']);
    Storage::disk('public')->assertExists($slides[0]['image_sm']);
});

it('leaves a configured hero slider untouched', function (): void {
    $settings = app(HomePageSettings::class);
    $settings->hero_slides = [['image' => 'hero/custom.webp', 'image_sm' => 'hero/custom-sm.webp', 'bg' => '#000000', 'position' => 'center']];
    $settings->save();

    seed(HeroSlidesSeeder::class);

    expect(app(HomePageSettings::class)->hero_slides)->toHaveCount(1);
});

it('backfills the 768w variant onto slides seeded before responsive hero images', function (): void {
    Storage::fake('public');

    $settings = app(HomePageSettings::class);
    $settings->hero_slides = [
        ['image' => 'hero/hero-rally.webp', 'bg' => '#0d1b38', 'position' => 'center top'],
        ['image' => 'hero/custom-upload.webp', 'bg' => '#000000', 'position' => 'center'],
    ];
    $settings->save();

    seed(HeroSlidesSeeder::class);

    $slides = app(HomePageSettings::class)->hero_slides;

    expect($slides[0]['image_sm'])->toBe('hero/hero-rally-sm.webp')
        ->and($slides[1])->not->toHaveKey('image_sm'); // admin upload left alone
});

it('deploy seeds content without wiping the admin login', function (): void {
    seed(RolesAndPermissionsSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    artisan('db:seed', ['--class' => ContentSeeder::class, '--force' => true])->assertSuccessful();

    expect(User::whereKey($admin->id)->exists())->toBeTrue();
});
