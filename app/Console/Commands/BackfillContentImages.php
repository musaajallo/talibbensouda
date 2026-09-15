<?php

namespace App\Console\Commands;

use App\Models\CommunityPhoto;
use App\Models\GalleryPhoto;
use App\Models\Project;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Attaches curated photos (resources/seed-images/) to Project, GalleryPhoto
 * and CommunityPhoto rows that don't have an image yet. Safe to re-run: a
 * row that already has media in the target collection is left untouched, so
 * an admin's own upload always wins and running this again after adding more
 * seed images only fills newly-added gaps.
 */
#[Signature('app:backfill-content-images')]
#[Description('Attach curated seed photos to content rows that have no image yet')]
class BackfillContentImages extends Command
{
    /** Project.key => resources/seed-images/project/<key>.jpg */
    private const PROJECTS = [
        'mbalit', 'ketp', 'roads', 'markets', 'health',
        'jobs', 'parks-community-centres', 'flood-prevention', 'governance-reform',
    ];

    /** CommunityPhoto.key => resources/seed-images/community-photo/<key>.jpg */
    private const COMMUNITY_PHOTOS = [
        'muni-waste', 'muni-youth', 'muni-markets', 'muni-environment',
        'muni-library', 'muni-roads',
        'cs-wards', 'cs-schools', 'cs-faith', 'cs-women', 'cs-sport', 'cs-skills',
    ];

    /** GalleryPhoto.caption => resources/seed-images/gallery-photo/<slug>.jpg */
    private const GALLERY_PHOTOS = [
        // Fourth batch (2026-09-14) — fills the last empty slots. Three of
        // these replace captions that had no genuine photo anywhere in the
        // source set (a Hungary/Barcelona/Taiwan twinning, a "Bakau Women's
        // Garden") — renamed to what's actually shown rather than force a
        // mismatched or fabricated photo onto the original claim.
        'Bundung Maternity Ward expansion' => 'bundung-maternity-ward-expansion',
        "KMC Women's Group reception" => 'kmc-womens-group-reception',
        'Manchester City Hall visit' => 'manchester-city-hall-visit',
        'China delegation — KMC partnership signing' => 'china-delegation-partnership-signing',
        'China delegation press briefing' => 'china-delegation-press-briefing',

        'Mbalit Project — new compactor fleet' => 'mbalit-project-new-compactor-fleet',
        'Serrekunda Market upgrade' => 'serrekunda-market-upgrade',
        'Bakoteh dumpsite fencing & remediation' => 'bakoteh-dumpsite-fencing-remediation',
        'KM SK West/Africell mini-stadium — Serrekunda West' => 'km-sk-west-africell-mini-stadium-serrekunda-west',
        'Kotu UN@75 Park rehabilitation' => 'kotu-un-75-park-rehabilitation',
        'Ward Development Committee office' => 'ward-development-committee-office',
        "Mayor's Trophy football tournament" => 'mayor-s-trophy-football-tournament',
        'Set settal community clean-up' => 'set-settal-community-clean-up',
        'Sewing machines for community centres' => 'sewing-machines-for-community-centres',
        'UN Deputy Secretary-General visit, 2025' => 'un-deputy-secretary-general-visit-2025',
        'Bakau Multipurpose Facility launch, 2025' => 'bakau-multipurpose-facility-launch-2025',
        'End-of-term awards night, 2022' => 'end-of-term-awards-night-2022',
        'Peterborough City Council — KETP partnership' => 'peterborough-city-council-ketp-partnership',
        'Sister-city ties — Freetown & Madison' => 'sister-city-ties-freetown-madison',
        'Global Parliament of Mayors' => 'global-parliament-of-mayors',

        // Reuse of photos already curated for Project/CommunityPhoto rows.
        'Kanifing Municipal Library & Innovation Hub' => 'kanifing-municipal-library-innovation-hub',
        'Road Network Project — Latrikunda' => 'road-network-project-latrikunda',
        'Municipal Library inauguration, 2024' => 'municipal-library-inauguration-2024',

        // Second batch (2026-09-14).
        'Community garden & water access programme' => 'community-garden-water-access',
        'Waste tipper truck handover, KMC fleet' => 'waste-tipper-truck-handover',
        'Waste bin distribution — Keep the City Clean' => 'waste-bin-distribution-keep-city-clean',
        'Serrekunda West Mini-Stadium foundation stone, 2022' => 'serrekunda-west-mini-stadium-foundation-stone',
        'Ya Fatou Njie Market foundation stone, Tallinding' => 'ya-fatou-njie-market-foundation-stone',
        "Bundung Community Park & Children's Playground event" => 'bundung-park-playground-event',
        'Market infrastructure walkabout' => 'market-infrastructure-walkabout',
        'Septic emptier trucks — KMC sanitation fleet' => 'septic-emptier-trucks-kmc-fleet',
        'Foundation stone laying ceremony' => 'foundation-stone-laying-ceremony',
        'Kanifing Community Radio studio' => 'kanifing-community-radio-studio',
        'Neighbourhood circle meeting with residents' => 'neighbourhood-circle-meeting',
        'Outdoor gym equipment — community park' => 'outdoor-gym-equipment-community-park',
        'Market community address' => 'market-community-address',
        'Street & site inspection tour' => 'street-site-inspection-tour',
        'Market outreach — greeting a resident' => 'market-outreach-greeting-resident',
        'Eid greetings — community religious event' => 'eid-greetings-community-event',
        'Islamic calendar gift presentation' => 'islamic-calendar-gift-presentation',
        'School compound visit' => 'school-compound-visit',
        'Basketball court opening' => 'basketball-court-opening',
        'Street cleanup & security patrol' => 'street-cleanup-security-patrol',
        'Banjul Africa Marathon — race day' => 'banjul-africa-marathon-race-day',
        'Gala Dinner 2025' => 'gala-dinner-2025',
        'Kanifing Municipal Police parade inspection' => 'kmp-parade-inspection',
        'Night-time ribbon-cutting ceremony' => 'night-ribbon-cutting-ceremony',
        'Key to Kanifing Municipality presentation' => 'key-to-municipality-presentation',
        'Gala event — traditional attire' => 'gala-event-traditional-attire',
        'Diamond Jubilee 60th anniversary parade' => 'diamond-jubilee-60th-anniversary-parade',
        'Ribbon-cutting before a large crowd' => 'ribbon-cutting-large-crowd',
        'Reception with community elders, chain of office' => 'elders-reception-chain-of-office',
        'Night-time community address' => 'night-time-community-address',
        'Office meeting with visiting delegation' => 'office-meeting-visiting-delegation',
        'Diplomatic visit — welcome ceremony' => 'diplomatic-visit-welcome-ceremony',
        'Madison Municipal Chamber visit' => 'madison-municipal-chamber-visit',
        'International Migration Review Forum, UN, 2022' => 'international-migration-review-forum-un-2022',
        'UN Economic Commission for Africa Forum — Kigali, Rwanda' => 'un-eca-forum-kigali-rwanda',
        'Gambia Police Force partnership visit' => 'gambia-police-force-partnership-visit',
        'BMZ Germany — Bakoteh Dumpsite Project foundation stone, 2021' => 'bmz-germany-bakoteh-dumpsite-foundation-stone',
        'Dane County, Wisconsin — landfill site visit' => 'dane-county-wisconsin-landfill-visit',
        'United Nations Headquarters — reception' => 'un-headquarters-reception',
        'International visitors — ribbon ceremony' => 'international-visitors-ribbon-ceremony',
        'UK university delegation visit' => 'uk-university-delegation-visit',
        'International delegation — project briefing' => 'international-delegation-project-briefing',
        'Freetown, Sierra Leone — partnership exchange' => 'freetown-sierra-leone-partnership-exchange',
        'Sierra Leone dignitary meeting' => 'sierra-leone-dignitary-meeting',
        'Partnership signing meeting' => 'partnership-signing-meeting',
        'Gambia Red Cross — COVID relief partnership' => 'gambia-red-cross-covid-relief',

        // Third batch (2026-09-14).
        'Colourful reception gathering' => 'colourful-reception-gathering',
        'Press briefing — emergency kit donation' => 'press-briefing-emergency-kit-donation',
        'Drainage & market inspection' => 'drainage-market-inspection',
        'Religious elder — office meeting' => 'religious-elder-office-meeting',
        'Madison College award reception, US trip' => 'us-madison-college-award-reception',
        'UK delegation visit, COVID era' => 'uk-delegation-covid-era-visit',
        'Community outdoor gathering & address' => 'community-outdoor-gathering-address',
        'Press conference — COVID-era facility opening' => 'covid-era-facility-press-conference',
        'Night reception with an international visitor' => 'night-reception-international-visitor',
        "Lord Mayor's office — trophies and awards" => 'lord-mayors-office-trophies',
        'Lord Mayor arrival, chain of office' => 'lord-mayor-arrival-chain-of-office',
        "'Don't Throw It' street campaign meeting" => 'dont-throw-it-street-meeting',
        'Health clinic tour, pink-walled ward' => 'health-clinic-tour-pink-walls',
        'Senior military officer meeting' => 'senior-military-officer-meeting',
        'Press conference on the KMC staircase' => 'press-conference-staircase-kmc',
        'Diplomatic office meeting, COVID era' => 'covid-era-diplomatic-office-meeting',
    ];

    public function handle(): int
    {
        $this->backfill(
            Project::class, self::PROJECTS, 'key', 'project', 'image',
        );

        $this->backfill(
            CommunityPhoto::class, self::COMMUNITY_PHOTOS, 'key', 'community-photo', 'photo',
        );

        $attached = 0;
        $skipped = 0;

        foreach (self::GALLERY_PHOTOS as $caption => $slug) {
            $photo = GalleryPhoto::where('caption', $caption)->first();

            if (! $photo) {
                $this->warn("No GalleryPhoto row found for caption: {$caption}");

                continue;
            }

            if ($photo->getFirstMedia('photo')) {
                $skipped++;

                continue;
            }

            $path = resource_path("seed-images/gallery-photo/{$slug}.jpg");

            if (! is_file($path)) {
                $this->warn("Missing seed image: {$path}");

                continue;
            }

            $photo->addMedia($path)->preservingOriginal()->toMediaCollection('photo');
            $attached++;
        }

        $this->info("GalleryPhoto: attached {$attached}, skipped {$skipped} (already had media)");

        return self::SUCCESS;
    }

    /**
     * @param  class-string<Project|CommunityPhoto>  $model
     * @param  string[]  $keys
     */
    private function backfill(string $model, array $keys, string $keyColumn, string $seedDir, string $collection): void
    {
        $attached = 0;
        $skipped = 0;

        foreach ($keys as $key) {
            $row = $model::where($keyColumn, $key)->first();

            if (! $row) {
                $this->warn("No {$model} row found for {$keyColumn}={$key}");

                continue;
            }

            if ($row->getFirstMedia($collection)) {
                $skipped++;

                continue;
            }

            $path = resource_path("seed-images/{$seedDir}/{$key}.jpg");

            if (! is_file($path)) {
                $this->warn("Missing seed image: {$path}");

                continue;
            }

            $row->addMedia($path)->preservingOriginal()->toMediaCollection($collection);
            $attached++;
        }

        $this->info("{$model}: attached {$attached}, skipped {$skipped} (already had media)");
    }
}
