<?php

use App\Models\CommunityPhoto;
use App\Models\Event;
use App\Models\Project;
use App\Models\Testimonial;
use App\Settings\HomePageSettings;
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

it('pulls recent milestones from upcoming events', function (): void {
    Event::create([
        'slug' => 'library-hub', 'title' => 'Municipal Library Hub', 'badge' => 'gambia',
        'date_day' => '01', 'date_month' => 'Dec', 'date_year' => '2024',
        'js_day' => 1, 'js_month' => 11, 'location' => 'Kanifing',
        'description' => 'D45m library inaugurated.', 'ics_start' => '20241201T090000Z', 'ics_end' => '20241201T160000Z',
        'is_upcoming' => true, 'sort_order' => 1,
    ]);

    get('/')->assertOk()->assertSee('Municipal Library Hub');
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
    $slides = app(HomePageSettings::class)->heroSlides();

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
