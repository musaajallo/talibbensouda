<?php

use App\Filament\Admin\Pages\ImportYoutubeVideos;
use App\Models\GalleryPhoto;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);

    config(['services.youtube.api_key' => 'test-key', 'services.youtube.channel_handle' => '@talibforpresident']);
});

function fakeYoutubeChannelAndUploads(): void
{
    Http::fake([
        'https://www.googleapis.com/youtube/v3/channels*part=id*' => Http::response([
            'items' => [['id' => 'UC_test_channel']],
        ]),
        'https://www.googleapis.com/youtube/v3/channels*part=contentDetails*' => Http::response([
            'items' => [['contentDetails' => ['relatedPlaylists' => ['uploads' => 'UU_test_uploads']]]],
        ]),
    ]);
}

it('shows a setup notice when no API key is configured', function (): void {
    config(['services.youtube.api_key' => null]);

    Livewire::test(ImportYoutubeVideos::class)
        ->assertSee('No YouTube API key configured');
});

it('lists the channel\'s uploaded videos', function (): void {
    fakeYoutubeChannelAndUploads();
    Http::fake([
        'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response([
            'items' => [
                ['snippet' => [
                    'title' => 'Rally in Bakau',
                    'publishedAt' => '2026-08-01T10:00:00Z',
                    'resourceId' => ['videoId' => 'abc123'],
                    'thumbnails' => ['medium' => ['url' => 'https://i.ytimg.com/vi/abc123/mqdefault.jpg']],
                ]],
            ],
            'nextPageToken' => 'PAGE2',
        ]),
    ]);

    Livewire::test(ImportYoutubeVideos::class)
        ->assertSee('Rally in Bakau')
        ->assertSet('nextPageToken', 'PAGE2');
});

it('searches the channel by title', function (): void {
    fakeYoutubeChannelAndUploads();
    Http::fake([
        // mount() browses the uploads playlist before the search is typed —
        // unfaked, that's a real network call.
        'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response(['items' => [], 'nextPageToken' => null]),
        'https://www.googleapis.com/youtube/v3/search*' => Http::response([
            'items' => [
                ['id' => ['videoId' => 'xyz789'], 'snippet' => [
                    'title' => 'Manifesto Launch',
                    'publishedAt' => '2026-08-10T10:00:00Z',
                    'thumbnails' => ['medium' => ['url' => 'https://i.ytimg.com/vi/xyz789/mqdefault.jpg']],
                ]],
            ],
            'nextPageToken' => null,
        ]),
    ]);

    Livewire::test(ImportYoutubeVideos::class)
        ->set('search', 'manifesto')
        ->assertSee('Manifesto Launch');
});

it('adds a picked video to the Gallery as a video-type row', function (): void {
    fakeYoutubeChannelAndUploads();
    Http::fake([
        'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response([
            'items' => [
                ['snippet' => [
                    'title' => 'Rally in Bakau',
                    'publishedAt' => '2026-08-01T10:00:00Z',
                    'resourceId' => ['videoId' => 'abc123'],
                    'thumbnails' => ['medium' => ['url' => 'https://i.ytimg.com/vi/abc123/mqdefault.jpg']],
                ]],
            ],
            'nextPageToken' => null,
        ]),
    ]);

    Livewire::test(ImportYoutubeVideos::class)
        ->callAction('addToGallery', data: [
            'category' => 'Events',
            'caption' => 'Rally in Bakau',
            'published' => true,
        ], arguments: [
            'videoId' => 'abc123',
            'title' => 'Rally in Bakau',
        ])
        ->assertHasNoActionErrors();

    $video = GalleryPhoto::where('youtube_video_id', 'abc123')->first();

    expect($video)->not->toBeNull()
        ->and($video->type)->toBe(GalleryPhoto::TYPE_VIDEO)
        ->and($video->category)->toBe('Events')
        ->and($video->caption)->toBe('Rally in Bakau')
        ->and($video->published)->toBeTrue();
});

it('refuses to add the same video twice', function (): void {
    GalleryPhoto::create([
        'caption' => 'Already here', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'abc123', 'published' => true, 'sort_order' => 0,
    ]);

    fakeYoutubeChannelAndUploads();

    Livewire::test(ImportYoutubeVideos::class)
        ->callAction('addToGallery', data: [
            'category' => 'Events',
            'caption' => 'Duplicate attempt',
            'published' => true,
        ], arguments: [
            'videoId' => 'abc123',
            'title' => 'Duplicate attempt',
        ]);

    expect(GalleryPhoto::where('youtube_video_id', 'abc123')->count())->toBe(1);
});

it('splits already-imported videos from ones still available to add', function (): void {
    GalleryPhoto::create([
        'caption' => 'Already here', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'abc123', 'published' => true, 'sort_order' => 0,
    ]);

    fakeYoutubeChannelAndUploads();
    Http::fake([
        'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response([
            'items' => [
                ['snippet' => [
                    'title' => 'Already Imported Video', 'publishedAt' => '2026-08-01T10:00:00Z',
                    'resourceId' => ['videoId' => 'abc123'], 'thumbnails' => ['medium' => ['url' => 'x']],
                ]],
                ['snippet' => [
                    'title' => 'Brand New Video', 'publishedAt' => '2026-08-02T10:00:00Z',
                    'resourceId' => ['videoId' => 'def456'], 'thumbnails' => ['medium' => ['url' => 'x']],
                ]],
            ],
            'nextPageToken' => null,
        ]),
    ]);

    $split = Livewire::test(ImportYoutubeVideos::class)
        // every card, imported or not, links out to the video on YouTube
        ->assertSeeHtml('href="https://www.youtube.com/watch?v=abc123"')
        ->assertSeeHtml('href="https://www.youtube.com/watch?v=def456"')
        ->assertSee('Open on YouTube')
        ->assertSee('Available to add')
        ->assertSee('Already in the Gallery')
        ->assertSee('Brand New Video')
        ->assertSee('Already Imported Video')
        ->instance()
        ->splitAvailableAndImported();

    expect(collect($split['available'])->pluck('id')->all())->toBe(['def456'])
        ->and(collect($split['imported'])->pluck('id')->all())->toBe(['abc123']);
});

it('previews a video with an autoplaying embed and a link to watch it on YouTube', function (): void {
    // mount() always calls loadVideos(), which resolves the channel/uploads
    // playlist — fake it even though this test doesn't care about the list,
    // or it falls through to a real, hanging network call.
    fakeYoutubeChannelAndUploads();
    Http::fake([
        'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response(['items' => [], 'nextPageToken' => null]),
    ]);

    Livewire::test(ImportYoutubeVideos::class)
        // mountAction + the modal assertions, not callAction/assertSee — the
        // preview has no submit (calling it closes the modal again), and the
        // modal body isn't part of the component's own html().
        ->mountAction('preview', arguments: [
            'videoId' => 'abc123',
            'title' => 'Rally in Bakau',
        ])
        ->assertMountedActionModalSeeHtml('autoplay=1')
        ->assertMountedActionModalSeeHtml('https://www.youtube.com/watch?v=abc123');
});
