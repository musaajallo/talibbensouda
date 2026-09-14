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

            // Second batch — grows the gallery to 100 photos (2026-09-14).
            ['Projects', 'Community garden & water access programme', false],
            ['Projects', 'Waste tipper truck handover, KMC fleet', false],
            ['Projects', 'Waste bin distribution — Keep the City Clean', false],
            ['Projects', 'Serrekunda West Mini-Stadium foundation stone, 2022', false],
            ['Projects', 'Ya Fatou Njie Market foundation stone, Tallinding', false],
            ['Projects', "Bundung Community Park & Children's Playground event", false],
            ['Projects', 'Market infrastructure walkabout', true],
            ['Projects', 'Septic emptier trucks — KMC sanitation fleet', false],
            ['Projects', 'Foundation stone laying ceremony', false],
            ['Projects', 'Kanifing Community Radio studio', false],
            ['Community', 'Neighbourhood circle meeting with residents', false],
            ['Community', 'Outdoor gym equipment — community park', false],
            ['Community', 'Market community address', false],
            ['Community', 'Street & site inspection tour', false],
            ['Community', 'Market outreach — greeting a resident', false],
            ['Community', 'Eid greetings — community religious event', false],
            ['Community', 'Islamic calendar gift presentation', false],
            ['Community', 'School compound visit', false],
            ['Community', 'Basketball court opening', false],
            ['Community', 'Street cleanup & security patrol', false],
            ['Events', 'Banjul Africa Marathon — race day', true],
            ['Events', 'Gala Dinner 2025', false],
            ['Events', 'Kanifing Municipal Police parade inspection', false],
            ['Events', 'Night-time ribbon-cutting ceremony', false],
            ['Events', 'Key to Kanifing Municipality presentation', false],
            ['Events', 'Gala event — traditional attire', false],
            ['Events', 'Diamond Jubilee 60th anniversary parade', true],
            ['Events', 'Ribbon-cutting before a large crowd', false],
            ['Events', 'Reception with community elders, chain of office', false],
            ['Events', 'Night-time community address', false],
            ['Partners', 'Office meeting with visiting delegation', false],
            ['Partners', 'Diplomatic visit — welcome ceremony', false],
            ['Partners', 'Madison Municipal Chamber visit', false],
            ['Partners', 'International Migration Review Forum, UN, 2022', true],
            ['Partners', 'UN Economic Commission for Africa Forum — Kigali, Rwanda', false],
            ['Partners', 'Gambia Police Force partnership visit', false],
            ['Partners', 'BMZ Germany — Bakoteh Dumpsite Project foundation stone, 2021', false],
            ['Partners', 'Dane County, Wisconsin — landfill site visit', false],
            ['Partners', 'United Nations Headquarters — reception', false],
            ['Partners', 'International visitors — ribbon ceremony', false],
            ['Partners', 'UK university delegation visit', false],
            ['Partners', 'International delegation — project briefing', false],
            ['Partners', 'Freetown, Sierra Leone — partnership exchange', false],
            ['Partners', 'Sierra Leone dignitary meeting', false],
            ['Partners', 'Partnership signing meeting', false],
            ['Partners', 'Gambia Red Cross — COVID relief partnership', false],

            // Third batch — final push toward 100.
            ['Community', 'Colourful reception gathering', false],
            ['Events', 'Press briefing — emergency kit donation', false],
            ['Projects', 'Drainage & market inspection', false],
            ['Community', 'Religious elder — office meeting', false],
            ['Partners', 'Madison College award reception, US trip', false],
            ['Partners', 'UK delegation visit, COVID era', false],
            ['Community', 'Community outdoor gathering & address', true],
            ['Events', 'Press conference — COVID-era facility opening', false],
            ['Community', 'Night reception with an international visitor', false],
            ['Events', "Lord Mayor's office — trophies and awards", false],
            ['Events', 'Lord Mayor arrival, chain of office', false],
            ['Community', "'Don't Throw It' street campaign meeting", false],
            ['Projects', 'Health clinic tour, pink-walled ward', false],
            ['Partners', 'Senior military officer meeting', false],
            ['Events', 'Press conference on the KMC staircase', false],
            ['Partners', 'Diplomatic office meeting, COVID era', false],
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
