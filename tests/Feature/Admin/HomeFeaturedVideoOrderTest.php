<?php

use App\Filament\Admin\Pages\ManageHomePage;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Settings\HomePageSettings;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

function orderedVideo(string $caption, array $overrides = []): GalleryPhoto
{
    return GalleryPhoto::create([
        'caption' => $caption, 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'v'.substr(md5($caption), 0, 10), 'published' => true, 'featured_on_home' => true, 'sort_order' => 0,
        ...$overrides,
    ]);
}

function saveVideoOrder(array $ids): void
{
    $home = app(HomePageSettings::class);
    $home->videos_order = $ids;
    $home->save();
}

describe('the order the featured videos come in', function (): void {
    it('follows the saved order, not the Gallery order', function (): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        $c = orderedVideo('Charlie');

        saveVideoOrder([$c->id, $a->id, $b->id]);

        expect(GalleryPhoto::featuredOnHome()->pluck('caption')->all())->toBe(['Charlie', 'Alpha', 'Bravo']);
    });

    it('puts a video featured after the last save at the end, in Gallery order', function (): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        saveVideoOrder([$b->id, $a->id]);

        orderedVideo('Delta', ['sort_order' => 2]);
        orderedVideo('Echo', ['sort_order' => 1]);

        expect(GalleryPhoto::featuredOnHome()->pluck('caption')->all())->toBe(['Bravo', 'Alpha', 'Echo', 'Delta']);
    });

    it('ignores ids that are no longer featured, published or even present', function (): void {
        $a = orderedVideo('Alpha');
        $unfeatured = orderedVideo('Bravo', ['featured_on_home' => false]);
        $draft = orderedVideo('Charlie', ['published' => false]);
        $b = orderedVideo('Delta');

        saveVideoOrder([$draft->id, 9999, $unfeatured->id, $b->id, $a->id]);

        expect(GalleryPhoto::featuredOnHome()->pluck('caption')->all())->toBe(['Delta', 'Alpha']);
    });

    it('is what the home page shows, in that order', function (): void {
        $a = orderedVideo('Alpha video');
        $b = orderedVideo('Bravo video');
        $c = orderedVideo('Charlie video');
        saveVideoOrder([$b->id, $c->id, $a->id]);

        $html = get('/')->assertOk()->getContent();

        expect(strpos($html, 'Bravo video'))->toBeLessThan(strpos($html, 'Charlie video'))
            ->and(strpos($html, 'Charlie video'))->toBeLessThan(strpos($html, 'Alpha video'));
    });

    it('leaves the fallback (nothing featured) in the Gallery order', function (): void {
        $a = orderedVideo('Alpha', ['featured_on_home' => false]);
        $b = orderedVideo('Bravo', ['featured_on_home' => false]);
        saveVideoOrder([$b->id, $a->id]); // a stale order must not reorder the fallback

        expect(GalleryPhoto::forHomeSlider()->pluck('caption')->all())->toBe(['Alpha', 'Bravo']);
    });

    it('does not change the order of the public Gallery', function (): void {
        $a = orderedVideo('Alpha video');
        $b = orderedVideo('Bravo video');
        saveVideoOrder([$b->id, $a->id]);

        $html = get('/gallery')->assertOk()->getContent();

        expect(strpos($html, 'Alpha video'))->toBeLessThan(strpos($html, 'Bravo video'));
    });
});

describe('on the Home page content screen', function (): void {
    beforeEach(function (): void {
        seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        actingAs($admin);
    });

    $listedIds = fn ($component): array => collect($component->get('data.videos_order'))->pluck('id')->values()->all();

    it('lists the featured videos, in the saved order', function () use ($listedIds): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        orderedVideo('Not featured', ['featured_on_home' => false]);
        saveVideoOrder([$b->id, $a->id]);

        $component = Livewire::test(ManageHomePage::class);

        expect($listedIds($component))->toBe([$b->id, $a->id]);
    });

    it('saves a new order, and the home page follows it', function () use ($listedIds): void {
        $a = orderedVideo('Alpha video');
        $b = orderedVideo('Bravo video');
        $c = orderedVideo('Charlie video');

        $component = Livewire::test(ManageHomePage::class);
        expect($listedIds($component))->toBe([$a->id, $b->id, $c->id]);

        // What dragging (or the arrows) does: the same rows in a new order.
        $reordered = collect($component->get('data.videos_order'))->sortBy(fn ($row) => [$c->id => 0, $a->id => 1, $b->id => 2][$row['id']])->values()->all();

        $component->fillForm(['videos_order' => $reordered])->call('save')->assertHasNoFormErrors();

        expect(app(HomePageSettings::class)->videos_order)->toBe([$c->id, $a->id, $b->id]);

        $html = get('/')->getContent();
        expect(strpos($html, 'Charlie video'))->toBeLessThan(strpos($html, 'Alpha video'))
            ->and(strpos($html, 'Alpha video'))->toBeLessThan(strpos($html, 'Bravo video'));
    });

    it('shows a video featured since the last save at the end', function () use ($listedIds): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        saveVideoOrder([$b->id, $a->id]);

        $later = orderedVideo('Later');

        expect($listedIds(Livewire::test(ManageHomePage::class)))->toBe([$b->id, $a->id, $later->id]);
    });

    it('drops a video that has been un-featured', function () use ($listedIds): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        saveVideoOrder([$a->id, $b->id]);

        $a->update(['featured_on_home' => false]);

        expect($listedIds(Livewire::test(ManageHomePage::class)))->toBe([$b->id]);
    });

    it('shows the explanation instead of an empty list when nothing is featured', function (): void {
        orderedVideo('Alpha', ['featured_on_home' => false]);

        Livewire::test(ManageHomePage::class)
            ->assertSee('No videos are featured yet')
            ->assertDontSee('Order of the featured videos');
    });

    it('does not let saving the page for other reasons scramble the order', function (): void {
        $a = orderedVideo('Alpha');
        $b = orderedVideo('Bravo');
        saveVideoOrder([$b->id, $a->id]);

        Livewire::test(ManageHomePage::class)
            ->fillForm(['videos_headline' => 'A new headline'])
            ->call('save')
            ->assertHasNoFormErrors();

        expect(app(HomePageSettings::class))->videos_headline->toBe('A new headline')
            ->and(app(HomePageSettings::class)->videos_order)->toBe([$b->id, $a->id]);
    });
});
