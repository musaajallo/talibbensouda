<?php

use App\Filament\Admin\Resources\CommunityPhotos\Pages\CreateCommunityPhoto;
use App\Filament\Admin\Resources\GivingProgrammes\Pages\CreateGivingProgramme;
use App\Filament\Admin\Resources\Projects\Pages\CreateProject;
use App\Filament\Admin\Resources\Testimonials\Pages\CreateTestimonial;
use App\Models\CommunityPhoto;
use App\Models\GivingProgramme;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);
});

it('creates a project with only the required fields', function (): void {
    Livewire::test(CreateProject::class)
        ->fillForm(['title' => 'Mbalit Waste System', 'description' => 'City-wide waste collection.', 'sort_order' => 1])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Project::where('title', 'Mbalit Waste System')->exists())->toBeTrue();
});

it('creates a testimonial with only the required fields', function (): void {
    Livewire::test(CreateTestimonial::class)
        ->fillForm(['quote' => 'Recognised internationally.', 'name' => 'New Castle County', 'sort_order' => 1])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Testimonial::where('name', 'New Castle County')->exists())->toBeTrue();
});

it('creates a giving programme with only the required fields', function (): void {
    Livewire::test(CreateGivingProgramme::class)
        ->fillForm(['title' => 'Back to School', 'description' => 'School supplies drive.', 'sort_order' => 1])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(GivingProgramme::where('title', 'Back to School')->exists())->toBeTrue();
});

it('creates a community photo with only the required fields', function (): void {
    Livewire::test(CreateCommunityPhoto::class)
        ->fillForm([
            'group' => CommunityPhoto::GROUP_MUNICIPALITY,
            'caption' => 'New library opened in 2024.',
            'sort_order' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(CommunityPhoto::where('caption', 'New library opened in 2024.')->exists())->toBeTrue();
});
