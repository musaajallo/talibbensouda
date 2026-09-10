<?php

use App\Models\CommunityPhoto;
use App\Models\GivingProgramme;
use App\Models\Project;
use App\Settings\AboutPageSettings;
use App\Settings\GivingBackPageSettings;
use App\Settings\PeoplesMayorPageSettings;

use function Pest\Laravel\get;

it('renders the People\'s Mayor page from settings + Project model', function (): void {
    $page = app(PeoplesMayorPageSettings::class);
    $page->hero_title = "The People's Mayor";
    $page->pull_quote = 'Revenue rose from D115m to D332m.';
    $page->save();

    Project::create(['key' => 'roads', 'title' => 'Road Network Project', 'description' => '38km of new roads.', 'published' => true, 'sort_order' => 1]);

    get('/peoples-mayor')
        ->assertOk()
        ->assertSee("The People's Mayor")
        ->assertSee('Revenue rose from D115m to D332m.')
        ->assertSee('Road Network Project');
});

it('splits community photos by group', function (): void {
    CommunityPhoto::create(['key' => 'a', 'group' => 'municipality', 'caption' => 'Municipal tile', 'published' => true, 'sort_order' => 1]);
    CommunityPhoto::create(['key' => 'b', 'group' => 'community-support', 'caption' => 'Giving Back tile', 'published' => true, 'sort_order' => 1]);

    get('/peoples-mayor')->assertSee('Municipal tile')->assertDontSee('Giving Back tile');
    get('/giving-back')->assertSee('Giving Back tile')->assertDontSee('Municipal tile');
});

it('renders the Giving Back page with programmes and repeater content', function (): void {
    $page = app(GivingBackPageSettings::class);
    $page->intro_headline = 'Money That Reaches the Ward Level';
    $page->enterprises = [['title' => 'Kanifing Municipal Transport', 'body' => 'Bus services.', 'role' => 'Council-owned']];
    $page->help_cards = [['title' => 'Volunteer', 'description' => 'Lend a hand.', 'cta_label' => 'Volunteer', 'cta_url' => '/contact']];
    $page->save();

    GivingProgramme::create(['key' => 'wdf', 'title' => 'Ward Development Funds', 'description' => 'D200,000 per ward.', 'published' => true, 'sort_order' => 1]);

    get('/giving-back')
        ->assertOk()
        ->assertSee('Money That Reaches the Ward Level')
        ->assertSee('Ward Development Funds')
        ->assertSee('Kanifing Municipal Transport')
        ->assertSee('Lend a hand.');
});

it('renders the About page with body paragraphs, timeline and pillars', function (): void {
    $page = app(AboutPageSettings::class);
    $page->bio_body = "First paragraph.\n\nSecond paragraph.";
    $page->timeline = [['year' => '2018', 'title' => 'Elected Lord Mayor', 'description' => 'At 31.']];
    $page->values = [['title' => 'Delivery', 'description' => 'Projects completed.']];
    $page->national_pillars = ['Transparent public finances'];
    $page->save();

    get('/about')
        ->assertOk()
        ->assertSee('First paragraph.')
        ->assertSee('Second paragraph.')
        ->assertSee('Elected Lord Mayor')
        ->assertSee('Transparent public finances');
});

it('renders every wired page with all content empty', function (string $path): void {
    get($path)->assertOk();
})->with(['/peoples-mayor', '/giving-back', '/about']);

it('uses the real domain, not the old placeholder, on the policy pages', function (string $path): void {
    get($path)
        ->assertOk()
        ->assertSee('info@talibahmedbensouda.com')
        ->assertDontSee('talibbensouda.gm');
})->with(['/privacy', '/cookies']);
