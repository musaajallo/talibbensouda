<?php

use App\Models\CommunityPhoto;
use App\Models\Event;
use App\Models\Project;
use App\Models\Testimonial;
use App\Settings\HomePageSettings;

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
