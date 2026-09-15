<?php

use App\Models\Event;
use App\Models\GivingProgramme;
use App\Models\HeroSlide;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
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

it('seeds the hero slider as HeroSlide rows backed by real media attachments', function (): void {
    Storage::fake('public');

    seed(HeroSlidesSeeder::class);

    $slides = HeroSlide::orderBy('sort_order')->get();

    expect($slides)->toHaveCount(6)
        ->and($slides->first()->position)->toBe('center top')
        ->and($slides->first()->imageUrl())->not->toBeNull();
});

it('leaves a configured hero slider untouched', function (): void {
    HeroSlide::create(['fallback_colour' => '#000000', 'position' => 'center', 'sort_order' => 0]);

    seed(HeroSlidesSeeder::class);

    expect(HeroSlide::count())->toBe(1);
});

it('deploy seeds content without wiping the admin login', function (): void {
    seed(RolesAndPermissionsSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    artisan('db:seed', ['--class' => ContentSeeder::class, '--force' => true])->assertSuccessful();

    expect(User::whereKey($admin->id)->exists())->toBeTrue();
});
