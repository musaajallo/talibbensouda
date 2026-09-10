<?php

namespace Database\Seeders;

use App\Models\CommunityPhoto;
use Illuminate\Database\Seeder;

class CommunityPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            ['Library', 'Municipal Library & Innovation Hub — opened 2024'],
            ['Waste', 'Mbalit household collection rollout'],
            ['Youth', 'Bakoteh Production & Innovation Centre'],
            ['Markets', 'Serrekunda Market upgrade'],
            ['Environment', 'Bakoteh dumpsite fencing & remediation'],
            ['Community', 'Ward development across 19 wards'],
        ];

        foreach ($photos as $i => [$tag, $caption]) {
            CommunityPhoto::updateOrCreate(
                ['caption' => $caption],
                ['tag' => $tag, 'published' => true, 'sort_order' => $i + 1],
            );
        }
    }
}
