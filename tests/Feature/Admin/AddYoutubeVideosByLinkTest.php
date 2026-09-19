<?php

use App\Filament\Admin\Pages\ImportYoutubeVideos;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Support\YouTube\AddVideosByLink;
use App\Support\YouTube\YouTubeUrl;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

/** The five links from the request that prompted this feature. */
const REQUESTED_LINKS = [
    'https://www.youtube.com/watch?v=6_sAiP0dJPU' => '6_sAiP0dJPU',
    'https://www.youtube.com/watch?v=o1pslItZX2k' => 'o1pslItZX2k',
    'https://www.youtube.com/watch?v=-CAc3sq7dnQ' => '-CAc3sq7dnQ', // an ID may start with a dash
    'https://www.youtube.com/watch?v=twRbdncbQC4' => 'twRbdncbQC4',
    'https://www.youtube.com/watch?v=ZOFFDhjqmVI' => 'ZOFFDhjqmVI',
];

function fakeOEmbed(array $titles = []): void
{
    Http::fake(function ($request) use ($titles) {
        parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);
        parse_str((string) parse_url($query['url'] ?? '', PHP_URL_QUERY), $watch);
        $id = $watch['v'] ?? '';

        // array_key_exists, not ??: an explicit null in $titles means "YouTube answers 404".
        $title = array_key_exists($id, $titles) ? $titles[$id] : "Title of {$id}";

        return $title === null
            ? Http::response('Not Found', 404)
            : Http::response(['title' => $title, 'author_name' => 'Some Channel']);
    });
}

describe('reading links', function (): void {
    it('extracts the id from every link in the request', function (): void {
        foreach (REQUESTED_LINKS as $url => $id) {
            expect(YouTubeUrl::extractId($url))->toBe($id);
        }
    });

    it('understands the other shapes a YouTube link takes', function (string $input): void {
        expect(YouTubeUrl::extractId($input))->toBe('dQw4w9WgXcQ');
    })->with([
        'watch with extra params' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=42s&list=PL123',
        'param order swapped' => 'https://www.youtube.com/watch?feature=share&v=dQw4w9WgXcQ',
        'short link' => 'https://youtu.be/dQw4w9WgXcQ',
        'short link with timestamp' => 'https://youtu.be/dQw4w9WgXcQ?t=10',
        'shorts' => 'https://www.youtube.com/shorts/dQw4w9WgXcQ',
        'embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'no-cookie embed' => 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
        'live' => 'https://www.youtube.com/live/dQw4w9WgXcQ',
        'mobile' => 'https://m.youtube.com/watch?v=dQw4w9WgXcQ',
        'no scheme' => 'youtube.com/watch?v=dQw4w9WgXcQ',
        'no www, http' => 'http://youtube.com/watch?v=dQw4w9WgXcQ',
        'bare id' => 'dQw4w9WgXcQ',
        'surrounding space' => "  https://youtu.be/dQw4w9WgXcQ \n",
    ]);

    it('rejects what is not a YouTube video', function (string $input): void {
        expect(YouTubeUrl::extractId($input))->toBeNull();
    })->with([
        'empty' => '',
        'another site' => 'https://vimeo.com/123456789',
        'a channel page' => 'https://www.youtube.com/@talibforpresident',
        'a playlist' => 'https://www.youtube.com/playlist?list=PL1234567890',
        'watch without v' => 'https://www.youtube.com/watch',
        'id too short' => 'https://youtu.be/abc',
        'a look-alike host' => 'https://notyoutube.com/watch?v=dQw4w9WgXcQ',
        'plain words' => 'hello there',
    ]);

    it('splits a pasted block on lines, spaces and commas, dropping repeats', function (): void {
        $parsed = YouTubeUrl::parseMany("https://youtu.be/aaaaaaaaaaa, https://youtu.be/bbbbbbbbbbb\nhttps://youtu.be/aaaaaaaaaaa   nonsense\n\nhttps://vimeo.com/1");

        expect($parsed['ids'])->toBe(['aaaaaaaaaaa', 'bbbbbbbbbbb'])
            ->and($parsed['invalid'])->toBe(['nonsense', 'https://vimeo.com/1']);
    });
});

describe('adding them', function (): void {
    beforeEach(function (): void {
        seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        actingAs($admin);

        config(['services.youtube.api_key' => null]); // the YouTube Videos page then skips its channel call
    });

    it('adds the five requested videos as published, featured Gallery videos', function (): void {
        fakeOEmbed(['6_sAiP0dJPU' => 'KETP Documentary', 'o1pslItZX2k' => 'KMC @ work']);

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => implode("\n", array_keys(REQUESTED_LINKS)),
                'category' => 'Projects',
                'published' => true,
                'featured_on_home' => true,
            ])
            ->assertHasNoActionErrors()
            ->assertNotified();

        $rows = GalleryPhoto::orderBy('id')->get();

        expect($rows)->toHaveCount(5)
            ->and($rows->pluck('youtube_video_id')->all())->toBe(array_values(REQUESTED_LINKS)) // pasted order
            ->and($rows->every(fn ($r) => $r->isVideo() && $r->published && $r->featured_on_home && $r->category === 'Projects'))->toBeTrue()
            ->and($rows[0]->caption)->toBe('KETP Documentary')  // caption comes from YouTube
            ->and($rows[1]->caption)->toBe('KMC @ work');

        // …and the home page's slider now shows only these five.
        expect(GalleryPhoto::forHomeSlider()->pluck('youtube_video_id')->all())->toBe(array_values(REQUESTED_LINKS));
    });

    it('is also available on the YouTube Videos page', function (): void {
        fakeOEmbed();

        Livewire::test(ImportYoutubeVideos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Events', 'published' => true, 'featured_on_home' => false,
            ])
            ->assertHasNoActionErrors();

        expect(GalleryPhoto::firstOrFail())
            ->youtube_video_id->toBe('6_sAiP0dJPU')
            ->category->toBe('Events')
            ->featured_on_home->toBeFalse();
    });

    it('does not feature them unless asked, and can add them unpublished', function (): void {
        fakeOEmbed();

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Projects', 'published' => false, 'featured_on_home' => false,
            ]);

        expect(GalleryPhoto::firstOrFail())->published->toBeFalse()->featured_on_home->toBeFalse();
    });

    it('never duplicates a video already in the Gallery, but features it when asked', function (): void {
        fakeOEmbed();
        $existing = GalleryPhoto::create([
            'caption' => 'Already here', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
            'youtube_video_id' => '6_sAiP0dJPU', 'published' => true, 'featured_on_home' => false, 'sort_order' => 3,
        ]);

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => "https://youtu.be/6_sAiP0dJPU\nhttps://youtu.be/o1pslItZX2k",
                'category' => 'Projects', 'published' => true, 'featured_on_home' => true,
            ]);

        expect(GalleryPhoto::count())->toBe(2)
            ->and($existing->refresh())
            ->caption->toBe('Already here')   // untouched …
            ->category->toBe('Events')
            ->sort_order->toBe(3)
            ->featured_on_home->toBeTrue();    // … except the flag the editor asked for
    });

    it('leaves an existing video alone when not featuring', function (): void {
        fakeOEmbed();
        $existing = GalleryPhoto::create([
            'caption' => 'Already here', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
            'youtube_video_id' => '6_sAiP0dJPU', 'published' => false, 'featured_on_home' => false, 'sort_order' => 0,
        ]);

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ]);

        expect($existing->refresh())->published->toBeFalse()->featured_on_home->toBeFalse();
    });

    it('skips a video YouTube says is private, removed or not embeddable', function (): void {
        fakeOEmbed(['o1pslItZX2k' => null]); // oEmbed answers 404

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => "https://youtu.be/6_sAiP0dJPU\nhttps://youtu.be/o1pslItZX2k", 'category' => 'Projects', 'published' => true, 'featured_on_home' => true,
            ])
            ->assertNotified();

        expect(GalleryPhoto::pluck('youtube_video_id')->all())->toBe(['6_sAiP0dJPU']);
    });

    it('treats YouTube\'s 400 for an ID that does not exist as "no such video"', function (): void {
        // Verified against the live endpoint: a made-up ID gets 400, not 404.
        Http::fake(['https://www.youtube.com/oembed*' => Http::response('Bad Request', 400)]);

        $result = app(AddVideosByLink::class)->handle('https://youtu.be/aaaaaaaaaaa', 'Projects', true, false);

        expect($result['unavailable'])->toBe(['aaaaaaaaaaa'])
            ->and($result['unchecked'])->toBe([])   // retrying would not help
            ->and(GalleryPhoto::count())->toBe(0);
    });

    it('lets a clean "Added" toast fade, but keeps one that names problem links on screen', function (): void {
        fakeOEmbed(['o1pslItZX2k' => null]);
        // Read the way Filament's own assertNotified() does: through its Notifications component.
        $lastToast = function (): Notification {
            $component = new Notifications;
            $component->mount();

            return $component->notifications->last();
        };

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ]);
        $clean = $lastToast();

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/o1pslItZX2k', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ]);
        $withProblem = $lastToast();

        // persistent() takes no argument — passing false used to leave EVERY toast persistent.
        expect($clean->getTitle())->toBe('Added 1 video to the Gallery')
            ->and($clean->getDuration())->not->toBe('persistent')
            ->and($withProblem->getTitle())->toBe('No new videos added')
            ->and($withProblem->getDuration())->toBe('persistent');
    });

    it('adds nothing, and says so, when YouTube cannot be reached', function (): void {
        Http::fake(fn () => throw new ConnectionException('timed out'));

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ])
            ->assertNotified();

        expect(GalleryPhoto::count())->toBe(0);
    });

    it('treats a server error from YouTube as "try again", not "no such video"', function (): void {
        Http::fake(['https://www.youtube.com/oembed*' => Http::response('oops', 500)]);

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ]);

        expect(GalleryPhoto::count())->toBe(0);
    });

    it('rejects a paste with no YouTube link in it', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => 'https://vimeo.com/123', 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ])
            ->assertHasActionErrors(['links']);
    });

    it('rejects an unreasonably long list', function (): void {
        $many = collect(range(1, 21))->map(fn (int $n) => 'https://youtu.be/'.str_pad((string) $n, 11, 'x', STR_PAD_LEFT))->implode("\n");

        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: [
                'links' => $many, 'category' => 'Projects', 'published' => true, 'featured_on_home' => false,
            ])
            ->assertHasActionErrors(['links']);

        expect(GalleryPhoto::count())->toBe(0);
    });

    it('needs a category', function (): void {
        Livewire::test(ListGalleryPhotos::class)
            ->callAction('addYoutubeVideosByLink', data: ['links' => 'https://youtu.be/6_sAiP0dJPU', 'category' => null])
            ->assertHasActionErrors(['category']);
    });
});
