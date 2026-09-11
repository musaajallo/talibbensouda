<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Support\EventFlyerPlaceholder;
use Illuminate\Http\Response;

/**
 * Serves the generated placeholder flyer for an event that has no uploaded
 * image. Deterministic from the event's own fields, so it's cacheable by URL —
 * no query string, ETag based on `updated_at`.
 */
class EventFlyerPlaceholderController extends Controller
{
    public function __invoke(Event $event): Response
    {
        $svg = EventFlyerPlaceholder::svgFor($event);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
            'ETag' => '"'.md5($event->slug.$event->updated_at?->timestamp).'"',
        ]);
    }
}
