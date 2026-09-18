<?php

use App\Filament\Admin\Resources\GalleryPhotos\Pages\EditGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ViewGalleryPhoto;
use App\Models\GalleryPhoto;
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

function youtubeGalleryEntry(): GalleryPhoto
{
    return GalleryPhoto::create([
        'caption' => 'Rally Highlights', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'abc123XYZ_-', 'published' => true, 'sort_order' => 0,
    ]);
}

it('shows a YouTube entry\'s video on its edit page instead of a photo uploader', function (): void {
    Livewire::test(EditGalleryPhoto::class, ['record' => youtubeGalleryEntry()->getKey()])
        ->assertSeeHtml('https://www.youtube-nocookie.com/embed/abc123XYZ_-')
        ->assertSeeHtml('https://www.youtube.com/watch?v=abc123XYZ_-')
        ->assertSee('Open on YouTube')
        ->assertFormFieldIsHidden('photo');
});

it('saves a YouTube entry without demanding a photo', function (): void {
    $entry = youtubeGalleryEntry();

    Livewire::test(EditGalleryPhoto::class, ['record' => $entry->getKey()])
        ->fillForm(['caption' => 'Renamed', 'category' => 'Projects', 'published' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($entry->refresh())
        ->caption->toBe('Renamed')
        ->category->toBe('Projects')
        ->published->toBeFalse()
        ->type->toBe(GalleryPhoto::TYPE_VIDEO)
        ->youtube_video_id->toBe('abc123XYZ_-');
});

it('still gives a photo entry the uploader, and still requires the photo', function (): void {
    $photo = GalleryPhoto::create([
        'caption' => 'Ward visit', 'category' => 'Projects', 'type' => GalleryPhoto::TYPE_PHOTO,
        'published' => true, 'sort_order' => 0,
    ]);

    Livewire::test(EditGalleryPhoto::class, ['record' => $photo->getKey()])
        ->assertFormFieldIsVisible('photo')
        ->assertDontSee('Open on YouTube')
        ->call('save')
        ->assertHasFormErrors(['photo' => 'required']);
});

it('shows the video on the read-only view page too', function (): void {
    Livewire::test(ViewGalleryPhoto::class, ['record' => youtubeGalleryEntry()->getKey()])
        ->assertSeeHtml('https://www.youtube-nocookie.com/embed/abc123XYZ_-');
});

it('lists YouTube entries with their thumbnail and a YouTube badge', function (): void {
    $entry = youtubeGalleryEntry();

    Livewire::test(ListGalleryPhotos::class)
        ->assertCanSeeTableRecords([$entry])
        ->assertSeeHtml('https://i.ytimg.com/vi/abc123XYZ_-/hqdefault.jpg')
        ->assertSee('YouTube');
});
