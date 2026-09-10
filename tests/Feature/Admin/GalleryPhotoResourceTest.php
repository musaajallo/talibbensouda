<?php

use App\Filament\Admin\Resources\GalleryPhotos\Pages\CreateGalleryPhoto;
use App\Models\GalleryPhoto;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);
});

it('creates a gallery photo with an uploaded image', function (): void {
    Livewire::test(CreateGalleryPhoto::class)
        ->fillForm([
            'caption' => 'Compactor fleet handover',
            'category' => 'Projects',
            'wide' => true,
            'published' => true,
            'sort_order' => 3,
            'photo' => [UploadedFile::fake()->image('fleet.jpg', 1600, 900)->size(200)],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $photo = GalleryPhoto::firstOrFail();

    expect($photo->caption)->toBe('Compactor fleet handover')
        ->and($photo->category)->toBe('Projects')
        ->and($photo->wide)->toBeTrue()
        ->and($photo->getFirstMedia('photo'))->not->toBeNull();
});

it('requires a photo', function (): void {
    Livewire::test(CreateGalleryPhoto::class)
        ->fillForm([
            'caption' => 'No image',
            'category' => 'Community',
        ])
        ->call('create')
        ->assertHasFormErrors(['photo' => 'required']);
});

it('exposes only published photos through the published scope', function (): void {
    GalleryPhoto::create(['caption' => 'Shown', 'category' => 'Events', 'published' => true, 'sort_order' => 1]);
    GalleryPhoto::create(['caption' => 'Hidden', 'category' => 'Events', 'published' => false, 'sort_order' => 2]);

    expect(GalleryPhoto::published()->pluck('caption')->all())->toBe(['Shown']);
});
