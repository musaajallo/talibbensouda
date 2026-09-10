<?php

namespace Database\Seeders;

use App\Models\CommunityPhoto;
use Illuminate\Database\Seeder;

class CommunityPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            // Home + People's Mayor "On the Ground" grid.
            ['muni-library', 'municipality', 'Library', 'Municipal Library & Innovation Hub — opened 2024'],
            ['muni-waste', 'municipality', 'Waste', 'Mbalit household collection rollout'],
            ['muni-youth', 'municipality', 'Youth', 'Bakoteh Production & Innovation Centre'],
            ['muni-markets', 'municipality', 'Markets', 'Serrekunda Market upgrade'],
            ['muni-environment', 'municipality', 'Environment', 'Bakoteh dumpsite fencing & remediation'],
            ['muni-roads', 'municipality', 'Roads', 'Road Network Project — Latrikunda'],

            // Giving Back "Community Support in Action" grid.
            ['cs-wards', 'community-support', 'Wards', 'Ward Development Committee office'],
            ['cs-schools', 'community-support', 'Schools', 'Scholarship support for KM students'],
            ['cs-faith', 'community-support', 'Faith', 'Mosque refurbishment support'],
            ['cs-women', 'community-support', 'Women', "Bakau Women's Garden — boreholes & compost"],
            ['cs-sport', 'community-support', 'Sport', "Mayor's Trophy community tournament"],
            ['cs-skills', 'community-support', 'Skills', 'Sewing machines for community centres'],
        ];

        foreach ($photos as $i => [$key, $group, $tag, $caption]) {
            CommunityPhoto::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'tag' => $tag,
                    'caption' => $caption,
                    'published' => true,
                    'sort_order' => $i + 1,
                ],
            );
        }
    }
}
