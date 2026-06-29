<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

/**
 * Stub feed source. Wire your model here, e.g.:
 *
 *   public function items(): Collection
 *   {
 *       return Post::published()->latest()->limit(20)->get();
 *   }
 *
 * Then make Post implement Spatie\Feed\Feedable.
 */
class FeedController extends Controller
{
    public function items(): Collection
    {
        return collect();
    }
}
