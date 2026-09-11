<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

/**
 * Backfills the gallery with the caption set that previously lived as a
 * hardcoded array in resources/views/gallery.blade.php. Photos are seeded
 * without images — an editor uploads the real photo per row in the panel.
 */
class GalleryPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            ['Projects', 'Mbalit Project — new compactor fleet', true],
            ['Projects', 'Kanifing Municipal Library & Innovation Hub', false],
            ['Projects', 'Road Network Project — Latrikunda', false],
            ['Projects', 'Serrekunda Market upgrade', false],
            ['Projects', 'Bakoteh dumpsite fencing & remediation', false],
            ['Projects', 'Bundung Maternity Ward expansion', false],
            ['Projects', 'KM SK West/Africell mini-stadium — Serrekunda West', true],
            ['Projects', 'Kotu UN@75 Park rehabilitation', false],
            ['Community', 'Ward Development Committee office', true],
            ['Community', "Bakau Women's Garden", false],
            ['Community', "Mayor's Trophy football tournament", false],
            ['Community', 'Set settal community clean-up', false],
            ['Community', 'Sewing machines for community centres', false],
            ['Events', 'Municipal Library inauguration, 2024', true],
            ['Events', 'UN Deputy Secretary-General visit, 2025', false],
            ['Events', 'Bakau Multipurpose Facility launch, 2025', false],
            ['Events', 'End-of-term awards night, 2022', false],
            ['Partners', 'Peterborough City Council — KETP partnership', true],
            ['Partners', 'Sister-city ties — Freetown & Madison', false],
            ['Partners', 'Global Parliament of Mayors', false],
            ['Partners', 'Twinning with Balassagyarmat, Hungary', false],
            ['Partners', 'Àrea Metropolitana de Barcelona partnership', false],
            ['Partners', 'Kaohsiung City, Taiwan partnership', false],
        ];

        foreach ($photos as $i => [$category, $caption, $wide]) {
            GalleryPhoto::firstOrCreate(
                ['caption' => $caption],
                [
                    'category' => $category,
                    'wide' => $wide,
                    'published' => true,
                    'sort_order' => $i + 1,
                ],
            );
        }
    }
}
