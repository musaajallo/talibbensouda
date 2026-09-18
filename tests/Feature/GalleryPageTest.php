<?php

use App\Models\GalleryPhoto;

use function Pest\Laravel\get;

it('includes a video row in the gallery data, distinct from photos', function (): void {
    GalleryPhoto::create([
        'caption' => 'Rally Highlights', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'abc123', 'published' => true, 'sort_order' => 0,
    ]);

    $html = get('/gallery')->assertOk()->getContent();

    expect($html)
        ->toContain('Rally Highlights')
        ->toContain('isVideo')
        ->toContain('i.ytimg.com')
        ->toContain('abc123')
        ->toContain('youtube-nocookie.com');
});

it('does not show an unpublished video', function (): void {
    GalleryPhoto::create([
        'caption' => 'Draft Video', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'draft123', 'published' => false, 'sort_order' => 0,
    ]);

    get('/gallery')->assertOk()->assertDontSee('Draft Video');
});

it('shows the media-type filter tabs', function (): void {
    get('/gallery')->assertOk()->assertSee('Videos')->assertSee('Photos');
});

it('sets the video embed to autoplay when the lightbox opens it', function (): void {
    GalleryPhoto::create([
        'caption' => 'Rally Highlights', 'category' => 'Events', 'type' => GalleryPhoto::TYPE_VIDEO,
        'youtube_video_id' => 'abc123', 'published' => true, 'sort_order' => 0,
    ]);

    get('/gallery')->assertOk()->assertSee('autoplay=1', false);
});

it('allows YouTube thumbnails through the CSP so a video poster actually loads', function (): void {
    $response = get('/gallery')->assertOk();

    expect($response->headers->get('Content-Security-Policy'))->toContain('i.ytimg.com');
});
