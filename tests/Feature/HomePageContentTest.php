<?php

use App\Models\CommunityPhoto;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\HeroSlide;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Testimonial;
use App\Settings\EventsPageSettings;
use App\Settings\HomePageSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;

it('renders home hero copy from settings', function (): void {
    $home = app(HomePageSettings::class);
    $home->hero_eyebrow = 'Lord Mayor of Kanifing';
    $home->hero_headline = "A record of delivery\nfor The Gambia";
    $home->hero_emphasis = 'delivery';
    $home->save();

    get('/')
        ->assertOk()
        ->assertSee('Lord Mayor of Kanifing')
        ->assertSee('<em>delivery</em>', escape: false);
});

it('renders projects, testimonials and community photos from their models', function (): void {
    Project::create(['title' => 'The Mbalit Project', 'description' => 'Waste system.', 'published' => true, 'sort_order' => 1]);
    Testimonial::create(['quote' => 'Recognised abroad.', 'name' => 'New Castle County', 'published' => true, 'sort_order' => 1]);
    CommunityPhoto::create(['tag' => 'Library', 'caption' => 'Library opened 2024', 'published' => true, 'sort_order' => 1]);

    get('/')
        ->assertOk()
        ->assertSee('The Mbalit Project')
        ->assertSee('New Castle County')
        ->assertSee('Library opened 2024');
});

it('pulls recent milestones from the Milestone model', function (): void {
    Milestone::create([
        'slug' => 'library-hub', 'title' => 'Municipal Library Hub',
        'occurred_on' => '2024-12-01', 'location' => 'Kanifing',
        'description' => 'D45m library inaugurated.', 'published' => true,
    ]);

    get('/')->assertOk()->assertSee('Municipal Library Hub');
});

it('shows genuinely upcoming events, not past ones, on the home page', function (): void {
    Event::create([
        'slug' => 'past-rally', 'title' => 'Past Rally Should Be Hidden', 'badge' => 'Campaign',
        'date_day' => '01', 'date_month' => 'Jan', 'date_year' => '2020',
        'js_day' => 1, 'js_month' => 0, 'location' => 'Kanifing',
        'description' => 'Long over.', 'ics_start' => '20200101T090000Z', 'ics_end' => '20200101T160000Z',
        'is_upcoming' => true,
    ]);

    get('/')->assertOk()->assertDontSee('Past Rally Should Be Hidden');
});

/** Create an Event on a given date, with every derived column filled in correctly. */
function homepageEventOn(Carbon $date, string $slug): Event
{
    return Event::create([
        'slug' => $slug, 'title' => $slug, 'badge' => 'Campaign',
        'date_day' => $date->format('j'), 'date_month' => $date->format('M'), 'date_year' => $date->format('Y'),
        'js_day' => (int) $date->format('j'), 'js_month' => (int) $date->format('n') - 1,
        'location' => 'Kanifing',
        'description' => 'An event.',
        'ics_start' => $date->format('Ymd').'T090000Z', 'ics_end' => $date->format('Ymd').'T160000Z',
        'is_upcoming' => true,
    ]);
}

it('only shows an upcoming event on the home page once it is within a week of happening', function (): void {
    homepageEventOn(now()->addDays(3), 'soon-on-home');
    homepageEventOn(now()->addDays(20), 'far-off-on-home');

    get('/')->assertOk()->assertSee('soon-on-home')->assertDontSee('far-off-on-home');
});

it('teases the count of events hidden beyond the one-week window on the home page', function (): void {
    homepageEventOn(now()->addDays(20), 'far-off-1');
    homepageEventOn(now()->addDays(25), 'far-off-2');

    get('/')->assertOk()->assertSee('+2')->assertSee('come back soon');
});

it('hides the home page upcoming-events section entirely when there is nothing to tease', function (): void {
    get('/')->assertOk()->assertDontSee('come back soon');
});

it('hides the home page Upcoming Events section when the settings kill switch is on', function (): void {
    homepageEventOn(now()->addDays(3), 'switched-off-home-event');
    Milestone::create([
        'slug' => 'library-hub', 'title' => 'Milestone Should Still Show On Home',
        'occurred_on' => '2024-12-01', 'location' => 'Kanifing',
        'description' => 'D45m library inaugurated.', 'published' => true,
    ]);

    $settings = app(EventsPageSettings::class);
    $settings->hide_events_sections = true;
    $settings->save();

    get('/')
        ->assertOk()
        ->assertDontSee('switched-off-home-event')
        ->assertDontSee('come back soon')
        ->assertSee('Milestone Should Still Show On Home');
});

it('renders with every content model empty', function (): void {
    get('/')->assertOk();
});

it('keeps content sections visible with an empty-state note when they have no data', function (): void {
    $home = app(HomePageSettings::class);
    $home->projects_headline = 'The People\'s Mayor';
    $home->recognition_headline = 'Recognition';
    $home->save();

    get('/')
        ->assertOk()
        ->assertSee('The People\'s Mayor')          // section header still rendered
        ->assertSee('Recognition')
        ->assertSee('empty-state', false)           // placeholder shown in place of the grid
        ->assertSee('will be published here soon.');
});

it('anchors hero slides to the top by default', function (): void {
    $slides = HeroSlide::heroSlides();

    expect($slides)->not->toBeEmpty()
        ->and(collect($slides)->pluck('pos')->unique()->all())->toBe(['center top']);
});

it('shows the bundled about portrait and feature video by default', function (): void {
    get('/')
        ->assertOk()
        ->assertSee('images/about-talib.webp', escape: false)
        ->assertSee('<video', escape: false)
        ->assertSee('videos/market-event.mp4', escape: false)
        ->assertDontSee('youtube-nocookie');
});

it('prefers an uploaded about image and video file over the bundled defaults', function (): void {
    $home = app(HomePageSettings::class);
    $home->about_image = 'home/portrait.jpg';
    $home->video_file = 'home/clip.mp4';
    $home->save();

    Storage::fake('public');
    Storage::disk('public')->put('home/portrait.jpg', 'x');
    Storage::disk('public')->put('home/clip.mp4', 'x');

    get('/')
        ->assertOk()
        ->assertSee('home/portrait.jpg', escape: false)
        ->assertSee('home/clip.mp4', escape: false);
});

it('falls back to a YouTube embed when an id is set and no file is uploaded', function (): void {
    $home = app(HomePageSettings::class);
    $home->video_youtube_id = 'abc123';
    $home->save();

    get('/')
        ->assertOk()
        ->assertSee('youtube-nocookie.com/embed/abc123', escape: false)
        ->assertDontSee('videos/market-event.mp4');
});

function homeVideo(string $id, string $caption, array $overrides = []): GalleryPhoto
{
    return GalleryPhoto::create([
        'caption' => $caption, 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => $id, 'published' => true, 'sort_order' => 0,
        ...$overrides,
    ]);
}

describe('home video slider', function (): void {
    it('shows the published Gallery videos, with thumbnail and embed', function (): void {
        homeVideo('vid111', 'Rally in Bakau');

        get('/')
            ->assertOk()
            ->assertSee('See the Work in Action')
            ->assertSee('Rally in Bakau')
            ->assertSee('https://i.ytimg.com/vi/vid111/hqdefault.jpg', false)
            // The embed URL sits in the slider's JSON-escaped Alpine data
            // (slashes and & are escaped), so match the parts that survive that.
            ->assertSee('youtube-nocookie.com', false)
            ->assertSee('vid111?autoplay=1', false);
    });

    it('leaves out unpublished videos and plain photos', function (): void {
        homeVideo('vid111', 'Shown video');
        homeVideo('vid222', 'Draft video', ['published' => false]);
        GalleryPhoto::create(['caption' => 'A photo caption', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_PHOTO, 'published' => true, 'sort_order' => 0]);

        get('/')
            ->assertOk()
            ->assertSee('Shown video')
            ->assertDontSee('Draft video')
            ->assertDontSee('vid222')
            ->assertDontSee('A photo caption');
    });

    it('hides the whole section while no video is published', function (): void {
        homeVideo('vid222', 'Draft video', ['published' => false]);

        get('/')
            ->assertOk()
            ->assertDontSee('See the Work in Action')
            ->assertDontSee('video-slider');
    });

    it('follows the Gallery\'s sort order and caps the strip at twelve', function (): void {
        foreach (range(1, 14) as $n) {
            homeVideo("vid{$n}", "Video number {$n}", ['sort_order' => 100 - $n]);
        }

        $html = get('/')->assertOk()->getContent();

        // Lowest sort_order first: 14, 13, ... — so the last two (1 and 2) fall off.
        expect(substr_count($html, 'class="video-slide"'))->toBe(12);
        expect($html)->toContain('Video number 14')->toContain('Video number 3')
            ->not->toContain('Video number 2"')->not->toContain('Video number 1"');
        expect(strpos($html, 'Video number 14'))->toBeLessThan(strpos($html, 'Video number 13'));
    });

    it('renders the editable copy and links to the Gallery\'s video filter', function (): void {
        homeVideo('vid111', 'Rally in Bakau');

        $home = app(HomePageSettings::class);
        $home->videos_eyebrow = 'Eyebrow canary';
        $home->videos_headline = 'Headline canary';
        $home->videos_lead = 'Lead canary';
        $home->videos_cta_label = 'CTA canary';
        $home->save();

        get('/')
            ->assertOk()
            ->assertSee('Eyebrow canary')
            ->assertSee('Headline canary')
            ->assertSee('Lead canary')
            ->assertSee('CTA canary')
            ->assertSee('/gallery?media=Videos', false);
    });

    it('puts the arrows beside the strip and keeps only the button below it', function (): void {
        homeVideo('vid111', 'Rally in Bakau');

        $html = get('/')->assertOk()->getContent();

        $stage = strpos($html, 'class="video-slider__stage"');
        $prev = strpos($html, 'video-slider__arrow--prev');
        $track = strpos($html, 'class="video-slider__track"');
        $next = strpos($html, 'video-slider__arrow--next');
        $controls = strpos($html, 'class="video-slider__controls"');

        // stage > prev arrow, track, next arrow — then the controls row (button only).
        expect($stage)->toBeLessThan($prev)
            ->and($prev)->toBeLessThan($track)
            ->and($track)->toBeLessThan($next)
            ->and($next)->toBeLessThan($controls)
            ->and($html)->not->toContain('video-slider__arrows');
    });

    it('sits directly below the community section', function (): void {
        homeVideo('vid111', 'Rally in Bakau');
        CommunityPhoto::create(['tag' => 'Library', 'caption' => 'Library opened 2024', 'published' => true, 'sort_order' => 1]);

        $html = get('/')->assertOk()->getContent();

        expect(strpos($html, 'Across the Municipality'))->toBeLessThan(strpos($html, 'See the Work in Action'));
    });
});
