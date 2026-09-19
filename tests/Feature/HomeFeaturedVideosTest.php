<?php

use App\Filament\Admin\Pages\ImportYoutubeVideos;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\EditGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use App\Models\GalleryPhoto;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

function featuredTestVideo(string $id, string $caption, array $overrides = []): GalleryPhoto
{
    return GalleryPhoto::create([
        'caption' => $caption, 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => $id, 'published' => true, 'featured_on_home' => false, 'sort_order' => 0,
        ...$overrides,
    ]);
}

describe('which videos the home slider shows', function (): void {
    it('shows every published video while none is featured', function (): void {
        featuredTestVideo('a1', 'Alpha video');
        featuredTestVideo('b2', 'Bravo video');
        featuredTestVideo('c3', 'Charlie draft', ['published' => false]);

        expect(GalleryPhoto::forHomeSlider()->pluck('caption')->all())->toBe(['Alpha video', 'Bravo video']);
    });

    it('shows only the featured ones once any is featured', function (): void {
        featuredTestVideo('a1', 'Alpha video');
        featuredTestVideo('b2', 'Bravo video', ['featured_on_home' => true]);
        featuredTestVideo('c3', 'Charlie video');

        expect(GalleryPhoto::forHomeSlider()->pluck('caption')->all())->toBe(['Bravo video']);

        get('/')->assertOk()->assertSee('Bravo video')->assertDontSee('Alpha video')->assertDontSee('Charlie video');
    });

    it('does not let a featured but unpublished video hide the rest', function (): void {
        featuredTestVideo('a1', 'Alpha video');
        featuredTestVideo('b2', 'Bravo draft', ['published' => false, 'featured_on_home' => true]);

        // The only featured video can't show, so it must not switch the section
        // to "featured only" — that would leave it empty.
        expect(GalleryPhoto::forHomeSlider()->pluck('caption')->all())->toBe(['Alpha video']);
    });

    it('ignores the flag on photos', function (): void {
        featuredTestVideo('a1', 'Alpha video');
        GalleryPhoto::create(['caption' => 'A featured photo', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_PHOTO, 'published' => true, 'featured_on_home' => true, 'sort_order' => 0]);

        expect(GalleryPhoto::forHomeSlider()->pluck('caption')->all())->toBe(['Alpha video']);
    });

    it('keeps the Gallery order among the featured videos, capped at twelve', function (): void {
        foreach (range(1, 14) as $n) {
            featuredTestVideo("v{$n}", "Featured {$n}", ['featured_on_home' => true, 'sort_order' => 100 - $n]);
        }
        featuredTestVideo('x', 'Not featured');

        $captions = GalleryPhoto::forHomeSlider()->pluck('caption');

        expect($captions)->toHaveCount(12)
            ->and($captions->first())->toBe('Featured 14')
            ->and($captions)->not->toContain('Not featured');
    });

    it('does not change what the public Gallery lists', function (): void {
        featuredTestVideo('a1', 'Alpha video');
        featuredTestVideo('b2', 'Bravo video', ['featured_on_home' => true]);

        get('/gallery')->assertOk()->assertSee('Alpha video')->assertSee('Bravo video');
    });
});

describe('in the admin panel', function (): void {
    beforeEach(function (): void {
        seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        actingAs($admin);
    });

    it('adds a video to the Gallery as featured from the import modal', function (): void {
        config(['services.youtube.api_key' => 'test-key', 'services.youtube.channel_handle' => '@talibforpresident']);
        Http::fake([
            'https://www.googleapis.com/youtube/v3/channels*part=id*' => Http::response(['items' => [['id' => 'UC_test']]]),
            'https://www.googleapis.com/youtube/v3/channels*part=contentDetails*' => Http::response(['items' => [['contentDetails' => ['relatedPlaylists' => ['uploads' => 'UU_test']]]]]),
            'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response(['items' => [], 'nextPageToken' => null]),
        ]);

        Livewire::test(ImportYoutubeVideos::class)
            ->callAction('addToGallery', data: [
                'category' => 'Events', 'caption' => 'Featured rally', 'published' => true, 'featured_on_home' => true,
            ], arguments: ['videoId' => 'feat123', 'title' => 'Featured rally'])
            ->assertHasNoActionErrors();

        expect(GalleryPhoto::where('youtube_video_id', 'feat123')->first())
            ->featured_on_home->toBeTrue()
            ->published->toBeTrue();
    });

    it('does not feature a video added without ticking the toggle', function (): void {
        config(['services.youtube.api_key' => 'test-key', 'services.youtube.channel_handle' => '@talibforpresident']);
        Http::fake([
            'https://www.googleapis.com/youtube/v3/channels*part=id*' => Http::response(['items' => [['id' => 'UC_test']]]),
            'https://www.googleapis.com/youtube/v3/channels*part=contentDetails*' => Http::response(['items' => [['contentDetails' => ['relatedPlaylists' => ['uploads' => 'UU_test']]]]]),
            'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response(['items' => [], 'nextPageToken' => null]),
        ]);

        Livewire::test(ImportYoutubeVideos::class)
            ->callAction('addToGallery', data: ['category' => 'Events', 'caption' => 'Plain', 'published' => true], arguments: ['videoId' => 'plain1', 'title' => 'Plain'])
            ->assertHasNoActionErrors();

        expect(GalleryPhoto::where('youtube_video_id', 'plain1')->first()->featured_on_home)->toBeFalse();
    });

    it('offers the toggle on a video entry only, and saves it', function (): void {
        $video = featuredTestVideo('a1', 'Alpha video');
        $photo = GalleryPhoto::create(['caption' => 'A photo', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_PHOTO, 'published' => true, 'sort_order' => 0]);

        Livewire::test(EditGalleryPhoto::class, ['record' => $photo->getKey()])
            ->assertFormFieldIsHidden('featured_on_home');

        Livewire::test(EditGalleryPhoto::class, ['record' => $video->getKey()])
            ->assertFormFieldIsVisible('featured_on_home')
            ->fillForm(['featured_on_home' => true])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($video->refresh()->featured_on_home)->toBeTrue();
    });

    it('marks featured videos in the Gallery list', function (): void {
        $featured = featuredTestVideo('a1', 'Alpha video', ['featured_on_home' => true]);
        $plain = featuredTestVideo('b2', 'Bravo video');

        Livewire::test(ListGalleryPhotos::class)
            ->assertCanSeeTableRecords([$featured, $plain])
            ->assertSee('Featured')
            ->assertSeeHtml('fi-color-warning');

        // …and shows no badge — and no empty star badge either — when nothing is featured.
        $featured->update(['featured_on_home' => false]);

        Livewire::test(ListGalleryPhotos::class)
            ->assertDontSee('Featured')
            ->assertDontSeeHtml('fi-color-warning');
    });
});
