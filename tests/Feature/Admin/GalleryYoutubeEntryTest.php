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

describe('filtering the Gallery list by media type', function (): void {
    beforeEach(function (): void {
        $this->photo = GalleryPhoto::create(['caption' => 'A ward visit', 'category' => 'Projects', 'type' => GalleryPhoto::TYPE_PHOTO, 'published' => true, 'sort_order' => 1]);
        $this->draftPhoto = GalleryPhoto::create(['caption' => 'A draft photo', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_PHOTO, 'published' => false, 'sort_order' => 2]);
        $this->video = youtubeGalleryEntry();
        $this->draftVideo = GalleryPhoto::create(['caption' => 'A draft video', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO, 'youtube_video_id' => 'draft123456', 'published' => false, 'sort_order' => 3]);
    });

    it('shows only videos', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->filterTable('type', GalleryPhoto::TYPE_VIDEO)
            ->assertCanSeeTableRecords([$this->video, $this->draftVideo])
            ->assertCanNotSeeTableRecords([$this->photo, $this->draftPhoto]);
    });

    it('shows only photos', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->filterTable('type', GalleryPhoto::TYPE_PHOTO)
            ->assertCanSeeTableRecords([$this->photo, $this->draftPhoto])
            ->assertCanNotSeeTableRecords([$this->video, $this->draftVideo]);
    });

    it('shows everything when no media type is chosen', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->assertCanSeeTableRecords([$this->photo, $this->draftPhoto, $this->video, $this->draftVideo]);
    });

    it('combines with the Published filter', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->filterTable('type', GalleryPhoto::TYPE_VIDEO)
            ->filterTable('published', false)
            ->assertCanSeeTableRecords([$this->draftVideo])
            ->assertCanNotSeeTableRecords([$this->video, $this->photo, $this->draftPhoto]);
    });

    it('combines with the category tabs', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->set('activeTab', 'Events')
            ->filterTable('type', GalleryPhoto::TYPE_VIDEO)
            ->assertCanSeeTableRecords([$this->video, $this->draftVideo])   // youtubeGalleryEntry() is in Events
            ->assertCanNotSeeTableRecords([$this->draftPhoto, $this->photo]);
    });
});
