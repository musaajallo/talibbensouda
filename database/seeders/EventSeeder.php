<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Milestones and public events drawn from KMC council records and public
     * reporting (2018–2026). Month-only dates use the first of the month.
     */
    public function run(): void
    {
        $events = [
            [
                'slug' => 'bakoteh-production-innovation-centre-2022',
                'title' => 'Bakoteh Production and Innovation Centre Inaugurated',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Dec',
                'date_year' => '2022',
                'js_day' => 1,
                'js_month' => 11,
                'location' => 'Bakoteh, Kanifing Municipality',
                'venue' => 'Bakoteh Production and Innovation Centre',
                'description' => 'Opening of a centre supporting training, production and commercialisation of textiles and hand-woven products, funded through the ITC Youth Empowerment Project and the EU.',
                'full_description' => "The Bakoteh Production and Innovation Centre (BPIC) was inaugurated in December 2022 with funding from the International Trade Centre's Youth Empowerment Project (YEP) and the European Union.\n\nThe centre supports young Gambians in the creative and textile industries, providing space and equipment for training, production and the commercialisation of hand-woven and printed products. It sits alongside the Council's wider youth skills work at Bakoteh, including an apprenticeship and skills-acquisition programme.",
                'ics_start' => '20221201T090000Z',
                'ics_end' => '20221201T160000Z',
                'is_upcoming' => true,
            ],
            [
                'slug' => 'kanifing-municipal-library-innovation-hub-2024',
                'title' => 'Kanifing Municipal Library and Innovation Hub Inaugurated',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Dec',
                'date_year' => '2024',
                'js_day' => 1,
                'js_month' => 11,
                'location' => 'Kanifing Municipality',
                'venue' => 'Kanifing Municipal Library and Innovation Hub',
                'description' => 'Inauguration of a D45 million public library and innovation hub delivered under the EU-funded Kanifing Environmental Transformation Programme.',
                'full_description' => "The Kanifing Municipal Library and Innovation Hub was inaugurated in December 2024, two years after its foundation stone was laid in August 2022. The D45 million facility was built by BAJAM Enterprise Limited and funded under the Kanifing Environmental Transformation Programme (KETP), a €3 million European Union grant delivered in partnership with Peterborough City Council in the UK.\n\nThe hub combines a public library, study and reading space, and a dedicated career and innovation centre. A Local Language Section — featuring ADLaN (Fula) and N'Ko (Manding) scripts — was added in April 2025.",
                'ics_start' => '20241201T090000Z',
                'ics_end' => '20241201T160000Z',
                'is_upcoming' => true,
            ],
            [
                'slug' => 'library-local-language-section-2025',
                'title' => 'Library Local Language Section Opens',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Apr',
                'date_year' => '2025',
                'js_day' => 1,
                'js_month' => 3,
                'location' => 'Kanifing Municipality',
                'venue' => 'Kanifing Municipal Library and Innovation Hub',
                'description' => 'A new section of the municipal library dedicated to Gambian languages, featuring the ADLaN (Fula) and N\'Ko (Manding) scripts.',
                'full_description' => "In April 2025 the Kanifing Municipal Library opened a Local Language Section, dedicated to reading and learning materials in Gambian languages. The section features the ADLaN script used for Fula and the N'Ko script used for Manding languages, supporting literacy and scholarship in languages that are widely spoken but rarely written.",
                'ics_start' => '20250401T090000Z',
                'ics_end' => '20250401T160000Z',
                'is_upcoming' => true,
            ],
            [
                'slug' => 'bakau-multipurpose-facility-2025',
                'title' => 'Bakau Multipurpose Facility Launched',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Jun',
                'date_year' => '2025',
                'js_day' => 1,
                'js_month' => 5,
                'location' => 'Bakau, Kanifing Municipality',
                'venue' => 'Bakau',
                'description' => 'Launch of a community multipurpose facility valued at more than D10 million, targeting around 520 beneficiaries, most of them women.',
                'full_description' => "A new multipurpose community facility in Bakau was launched in June 2025. Valued at more than D10 million, it is designed to serve around 520 beneficiaries, the majority of them women, with space for skills training, small enterprise and community activity. It builds on the Council's earlier work rebuilding the Bakau Community Centre and expanding the Bakoteh Multipurpose Centre.",
                'ics_start' => '20250601T090000Z',
                'ics_end' => '20250601T160000Z',
                'is_upcoming' => true,
            ],
            [
                'slug' => 'un-deputy-secretary-general-visit-2025',
                'title' => 'UN Deputy Secretary-General Visits Bakoteh Youth Skills Centre',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Jul',
                'date_year' => '2025',
                'js_day' => 1,
                'js_month' => 6,
                'location' => 'Bakoteh, Kanifing Municipality',
                'venue' => 'Bakoteh Youth Skills Acquisition Centre',
                'description' => 'UN Deputy Secretary-General Amina J. Mohammed visited the Council\'s youth skills centre at Bakoteh during a visit to The Gambia.',
                'full_description' => "In July 2025, United Nations Deputy Secretary-General Amina J. Mohammed visited the Bakoteh Youth Skills Acquisition Centre. At the time of the visit the centre had around 40 students in active training and a further 20 in apprenticeship programmes, working in trades linked to the Council's production and recycling facilities at the site.",
                'ics_start' => '20250701T090000Z',
                'ics_end' => '20250701T160000Z',
                'is_upcoming' => true,
            ],
            [
                'slug' => 'digital-addresses-policymaking-2026',
                'title' => 'Digital Addresses for Evidence-Based Policymaking Launched',
                'badge' => 'Kanifing',
                'date_day' => '01',
                'date_month' => 'Jun',
                'date_year' => '2026',
                'js_day' => 1,
                'js_month' => 5,
                'location' => 'Kanifing Municipality',
                'venue' => 'Kanifing Municipal Council',
                'description' => 'Launch of a research partnership using KMC\'s digital addressing system to strengthen data-driven municipal governance, with Paris Dauphine University, Sciences Po Paris and the University of The Gambia.',
                'full_description' => "In June 2026 the Council launched \"Leveraging Digital Addresses for Evidence-Based Policymaking,\" a research partnership with Paris Dauphine University, Sciences Po Paris and the University of The Gambia, funded by the Fund for Innovation in Development (FID).\n\nThe project builds on KMC's digital addressing system, which has assigned Google Plus Codes to 31,867 properties across the municipality. It aims to use that address data to improve navigation, emergency response, service delivery and revenue collection, and to support better-evidenced municipal policy.",
                'ics_start' => '20260601T090000Z',
                'ics_end' => '20260601T160000Z',
                'is_upcoming' => true,
            ],
        ];

        foreach ($events as $data) {
            Event::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
