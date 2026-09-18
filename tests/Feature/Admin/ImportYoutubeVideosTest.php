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
