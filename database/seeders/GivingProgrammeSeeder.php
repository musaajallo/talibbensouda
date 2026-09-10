<?php

namespace Database\Seeders;

use App\Models\GivingProgramme;
use Illuminate\Database\Seeder;

class GivingProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            [
                'key' => 'ward-development-funds',
                'title' => 'Ward Development Funds',
                'description' => 'Every one of the 19 Ward Development Committees receives an annual development fund of D200,000. The Council has also opened, furnished and paid the rent on a WDC office in each ward, equipped with computers, furniture and prepaid power.',
                'metric' => 'D3.8M to WDCs per year',
            ],
            [
                'key' => 'support-for-alkalis',
                'title' => 'Support for Alkalis',
                'description' => 'Sixteen community heads (Alkalis) across Kanifing Municipality receive stipends from the Council, recognising their role in local dispute resolution, land matters and day-to-day community leadership.',
                'metric' => 'D2.2M to Alkalis per year',
            ],
            [
                'key' => 'religious-support',
                'title' => 'Religious Support',
                'description' => 'The Council contributes around D1 million a year toward mosque refurbishment and around D1.5 million a year toward religious gatherings such as Gamos and conferences, alongside regular visits to worship with congregations.',
                'metric' => '~D2.5M per year',
            ],
            [
                'key' => 'scholarships',
                'title' => 'Scholarships & School Support',
                'description' => 'More than D6 million in scholarships to Kanifing Municipality students over six years, plus over D12 million in grants, scholarships and donations to KM schools — and an annual D2.4 million subvention to Charles Jow Memorial Academy.',
                'metric' => 'D6M+ in scholarships / 6 yrs',
            ],
            [
                'key' => 'sport-recreation',
                'title' => 'Sport & Recreation',
                'description' => "The Mayor's Trophy football tournament costs around D1 million to run, with D350,000 paid out in prizes to community teams. A further D500,000 has gone toward Gambian athletics, and buffer-zone pitches have been upgraded with new goalposts and 180 solar lights.",
                'metric' => 'D350k in community team prizes',
            ],
            [
                'key' => 'womens-livelihoods',
                'title' => "Women's Livelihoods",
                'description' => "Cold-storage facilities for women vendors at Serrekunda Market and for Denton Bridge oyster sellers; boreholes for the Bakoteh Women's Garden; compost machinery at the Bakau Women's Garden; and 155 sewing machines for women in tailoring alongside youth.",
                'metric' => "D100M toward women's enterprise",
            ],
        ];

        foreach ($programmes as $i => $data) {
            GivingProgramme::firstOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['published' => true, 'sort_order' => $i + 1]),
            );
        }
    }
}
