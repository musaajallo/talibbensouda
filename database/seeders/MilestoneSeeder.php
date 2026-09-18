<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    /**
     * The past record of delivery — elections, launches and openings — drawn
     * from KMC council records and public reporting (2018–2026). Month-only
     * dates use the first of the month. Previously split between a hardcoded
     * array on the events page and rows in the `events` table; unified here
     * when Events and Milestones became separate resources.
     */
    public function run(): void
    {
        $milestones = [
            [
                'slug' => 'elected-lord-mayor-2018',
                'title' => 'Elected Lord Mayor of Kanifing',
                'occurred_on' => '2018-05-01',
                'location' => 'Kanifing Municipal Council',
                'description' => 'Talib Ahmed Bensouda elected Lord Mayor of Kanifing Municipal Council.',
            ],
            [
                'slug' => 'mbalit-project-launched-2019',
                'title' => 'Mbalit Project Launched',
                'occurred_on' => '2019-01-01',
                'location' => 'Kanifing Municipality',
                'description' => 'Launch of the Mbalit waste-management project across the municipality.',
            ],
            [
                'slug' => 'ketp-begins-2021',
                'title' => 'Kanifing Environmental Transformation Programme Begins',
                'occurred_on' => '2021-01-01',
                'location' => 'KMC & Peterborough City Council',
                'description' => 'Start of the EU-funded Kanifing Environmental Transformation Programme, delivered in partnership with Peterborough City Council.',
            ],
            [
                'slug' => 'wasteaid-composting-pilot-2021',
                'title' => 'WasteAid Composting Pilot Launched',
                'occurred_on' => '2021-07-01',
                'location' => 'Bakau & Abuko',
                'description' => 'Launch of a community composting pilot with WasteAid in Bakau and Abuko.',
            ],
            [
                'slug' => 'library-foundation-stone-2022',
                'title' => 'Municipal Library Foundation Stone Laid',
                'occurred_on' => '2022-08-01',
                'location' => 'Kanifing Municipality',
                'description' => 'Foundation stone laid for what would become the Kanifing Municipal Library and Innovation Hub.',
            ],
            [
                'slug' => 'bakoteh-production-innovation-centre-2022',
                'title' => 'Bakoteh Production and Innovation Centre Inaugurated',
                'occurred_on' => '2022-12-01',
                'location' => 'Bakoteh, Kanifing Municipality',
                'description' => 'Opening of a centre supporting training, production and commercialisation of textiles and hand-woven products, funded through the ITC Youth Empowerment Project and the EU.',
            ],
            [
                'slug' => 'end-of-term-awards-night-2022',
                'title' => 'End-of-Term Awards Night',
                'occurred_on' => '2022-12-01',
                'location' => 'Kanifing Municipal Council',
                'description' => 'Council-wide awards night marking the end of the first term.',
            ],
            [
                'slug' => 're-elected-lord-mayor-2023',
                'title' => 'Re-elected Lord Mayor of Kanifing',
                'occurred_on' => '2023-05-01',
                'location' => 'Kanifing Municipal Council',
                'description' => 'Talib Ahmed Bensouda re-elected Lord Mayor of Kanifing Municipal Council.',
            ],
            [
                'slug' => 'kanifing-municipal-library-innovation-hub-2024',
                'title' => 'Kanifing Municipal Library and Innovation Hub Inaugurated',
                'occurred_on' => '2024-12-01',
                'location' => 'Kanifing Municipality',
                'description' => 'Inauguration of a D45 million public library and innovation hub delivered under the EU-funded Kanifing Environmental Transformation Programme.',
            ],
            [
                'slug' => 'library-local-language-section-2025',
                'title' => 'Library Local Language Section Opens',
                'occurred_on' => '2025-04-01',
                'location' => 'Kanifing Municipality',
                'description' => 'A new section of the municipal library dedicated to Gambian languages, featuring the ADLaN (Fula) and N\'Ko (Manding) scripts.',
            ],
            [
                'slug' => 'bakau-multipurpose-facility-2025',
                'title' => 'Bakau Multipurpose Facility Launched',
                'occurred_on' => '2025-06-01',
                'location' => 'Bakau, Kanifing Municipality',
                'description' => 'Launch of a community multipurpose facility valued at more than D10 million, targeting around 520 beneficiaries, most of them women.',
            ],
            [
                'slug' => 'un-deputy-secretary-general-visit-2025',
                'title' => 'UN Deputy Secretary-General Visits Bakoteh Youth Skills Centre',
                'occurred_on' => '2025-07-01',
                'location' => 'Bakoteh, Kanifing Municipality',
                'description' => 'UN Deputy Secretary-General Amina J. Mohammed visited the Council\'s youth skills centre at Bakoteh during a visit to The Gambia.',
            ],
            [
                'slug' => 'digital-addresses-policymaking-2026',
                'title' => 'Digital Addresses for Evidence-Based Policymaking Launched',
                'occurred_on' => '2026-06-01',
                'location' => 'Kanifing Municipality',
                'description' => 'Launch of a research partnership using KMC\'s digital addressing system to strengthen data-driven municipal governance, with Paris Dauphine University, Sciences Po Paris and the University of The Gambia.',
            ],
        ];

        foreach ($milestones as $data) {
            Milestone::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
