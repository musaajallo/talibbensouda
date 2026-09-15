<?php

use App\Models\HeroSlide;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * The hero slider used to be a Repeater on HomePageSettings storing raw
 * `public`-disk paths (`hero/hero-rally.webp`). It's now its own resource
 * (HeroSlide) backed by real Media Library attachments, so the panel can
 * offer a proper List/Create/Edit/Delete + drag-and-drop reorder instead of
 * a settings-page repeater. This carries over whatever was configured —
 * bundled defaults or admin uploads alike — before the settings key is
 * dropped in the next migration.
 *
 * Reads the raw settings row via the query builder rather than
 * HomePageSettings — that class no longer declares `hero_slides` (removed
 * alongside this migration), so it can't hydrate the old value.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (HeroSlide::query()->exists()) {
            return;
        }

        $payload = DB::table('settings')
            ->where('group', 'home_page')
            ->where('name', 'hero_slides')
            ->value('payload');

        $slides = $payload ? json_decode($payload, true) : [];

        if (empty($slides)) {
            return;
        }

        foreach (array_values($slides) as $i => $slide) {
            $path = $slide['image'] ?? null;

            if (! is_string($path) || ! Storage::disk('public')->exists($path)) {
                continue;
            }

            $heroSlide = HeroSlide::create([
                'fallback_colour' => $slide['bg'] ?? '#0d1b38',
                'position' => $slide['position'] ?? 'center top',
                'sort_order' => $i,
            ]);

            $heroSlide->addMediaFromDisk($path, 'public')->toMediaCollection('image');
        }
    }

    public function down(): void
    {
        // Irreversible: media attachments can't be turned back into raw
        // settings paths. Re-run the settings migration by hand if needed.
    }
};
