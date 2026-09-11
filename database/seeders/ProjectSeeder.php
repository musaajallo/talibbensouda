<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * The six flagship projects. `summary` drives the home page card; the full
 * `description` + `metrics` drive the People's Mayor showcase. `firstOrCreate`
 * on `key`: creates missing rows on deploy, never touches rows that already
 * exist, so edits made in the admin panel are preserved.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'key' => 'mbalit',
                'tag' => 'Waste Management',
                'title' => 'The Mbalit Project',
                'summary' => "Kanifing's first municipality-wide waste collection system — a fully funded D130 million partnership with 24 new compactor trucks and 172 youth jobs.",
                'description' => "Kanifing's first structured, municipality-wide waste collection system — and the first of its kind in the sub-region. Delivered as a three-year, fully funded D130 million partnership with QGroup, the project introduced organised household waste collection with 24 new compactor trucks, skip trucks, septic emptiers and tipper trucks. The Council paid off the facility in full and secured ownership of all vehicles, and employed 172 young people as janitors, drivers, ticket agents and secretaries.",
                'metrics' => [
                    ['value' => 'D130M', 'label' => 'Fully Funded'],
                    ['value' => '24', 'label' => 'Compactor Trucks'],
                    ['value' => '172', 'label' => 'Youth Jobs'],
                ],
            ],
            [
                'key' => 'ketp',
                'tag' => 'Environment',
                'title' => 'Kanifing Environmental Transformation Programme',
                'summary' => 'A €3 million EU-funded programme covering waste, education and tree planting — including the plan to plant 190,000 trees across 19 wards.',
                'description' => "KMC's flagship environmental programme, funded by a €3 million European Union grant and delivered in partnership with Peterborough City Council in the UK. KETP covers waste management, environmental education and tree planting. It funded the Kanifing Municipal Library and Innovation Hub, the \"one garbage can per household\" rollout of 10,000 bins, community transfer stations for waste sorting, recycling infrastructure at Bakoteh, and a plan to plant 190,000 trees across the municipality's 19 wards.",
                'metrics' => [
                    ['value' => '€3M', 'label' => 'EU Grant'],
                    ['value' => '10,000', 'label' => 'Bins Distributed'],
                    ['value' => '190,000', 'label' => 'Trees Planned'],
                ],
            ],
            [
                'key' => 'roads',
                'tag' => 'Roads & Drainage',
                'title' => 'Kanifing Municipal Road Network Project',
                'summary' => 'More than D300 million of Council funding for 38 kilometres of new roads, 11 feeder roads and two bridges connecting all 19 wards.',
                'description' => "A Council-funded programme of more than D300 million to build 38 kilometres of new roads connecting KMC's 19 wards — including 11 feeder roads, two bridges and 5.9 kilometres of new drainage. It builds on earlier Council road works on Lat Kumba Road, Bakau Marina Road, and the Latrikunda–Wellingara and Kololi–Manjai roads, and complements national road efforts by the OIC and NRA.",
                'metrics' => [
                    ['value' => 'D300M+', 'label' => 'Council-Funded'],
                    ['value' => '38km', 'label' => 'New Roads'],
                    ['value' => '19', 'label' => 'Wards Connected'],
                ],
            ],
            [
                'key' => 'markets',
                'tag' => 'Markets',
                'title' => 'Market Construction & Rehabilitation',
                'summary' => "Latrikunda Sabiji rebuilt with 100 shops; Serrekunda Market upgraded with a women's shed, CCTV, boreholes and security lighting.",
                'description' => "New and rehabilitated markets to give vendors — particularly women traders — safe, dignified space to earn a living. Latrikunda Sabiji Market was rebuilt as a storey building with 100 shops. Serrekunda Market received a major upgrade with a 30-kiosk women's shed, rehabilitated drains, CCTV, boreholes and 60 security lights. Markets at Tallinding, Old Bakau and Latrikunda Yiriganya were rehabilitated, and a Council-owned company, Kanifing Municipal Markets Ltd, is expanding the network further.",
                'metrics' => [
                    ['value' => '100', 'label' => 'Shops at Latrikunda Sabiji'],
                    ['value' => '19 → 26', 'label' => 'Markets Planned'],
                    ['value' => '30', 'label' => "Women's Kiosks — Serrekunda"],
                ],
            ],
            [
                'key' => 'health',
                'tag' => 'Health',
                'title' => 'Healthcare Access',
                'summary' => 'Nine ambulances for nine community clinics, a D15 million expansion of the Bundung Maternity Ward, and support for local clinics.',
                'description' => "KMC expanded the Bundung Maternity Ward in a D15 million partnership with Gamworks, rehabilitated the Council's maternity ward at Serekunda Hospital, and provided support to community clinics in Ebo Town and Tallinding. Nine ambulances were provided to nine community clinics across Kanifing Municipality so that emergencies could be reached faster.",
                'metrics' => [
                    ['value' => '9', 'label' => 'Ambulances to 9 Clinics'],
                    ['value' => 'D15M', 'label' => 'Bundung Maternity Ward'],
                    ['value' => '3', 'label' => 'Community Clinics Supported'],
                ],
            ],
            [
                'key' => 'jobs',
                'tag' => 'Youth & Women',
                'title' => 'Jobs, Skills and Enterprise',
                'summary' => 'A D20 million youth revolving fund, the "Tekki Fii" innovation challenge, skills centres and 155 sewing machines for community centres.',
                'description' => "A D20 million Youth Revolving Fund provides soft loans to young entrepreneurs. The Mayor's \"Tekki Fii\" Innovative Challenge, run with the EU and the Youth Empowerment Project, has awarded startup grants to Gambian innovators. The Bakoteh Production and Innovation Centre, opened in December 2022, supports training and production in textiles and hand-woven goods, and 155 sewing machines were distributed to skills and community centres — alongside financing and cold-storage facilities aimed at women-led enterprise.",
                'metrics' => [
                    ['value' => 'D20M', 'label' => 'Youth Revolving Fund'],
                    ['value' => '155', 'label' => 'Sewing Machines'],
                    ['value' => 'D100M', 'label' => "Toward Women's Enterprise"],
                ],
            ],
            [
                'key' => 'parks-community-centres',
                'tag' => 'Parks & Recreation',
                'title' => 'Parks, Stadiums & Community Centres',
                'summary' => 'A D100 million mini-stadium in Serrekunda West, a UNDP-backed rebuild of Kotu Park, and community centres re-equipped across the municipality.',
                'description' => "The Council has invested in parks, sports facilities and community centres across Kanifing. In partnership with UNDP, Kotu UN@75 Park (formerly Kotu Park) was rehabilitated with a new wall, an outdoor gym and a children's playground. The Bakau Community Centre was rebuilt with the Youth Empowerment Project, and the Bakoteh Multipurpose Centre was expanded and equipped with sewing and t-shirt printing machines in a D10 million YEP-backed investment. The Kanifing Community Centre was rehabilitated and handed over to local youth, and the Westfield-to-Jimpex corridor was beautified. In partnership with Africell, the Council built a D8 million park at the Traffic Light junction, with a KM Qpark now in its launch phase alongside it — and, in Serrekunda West, a D100 million KM SK West/Africell mini-stadium, complete with new goalposts, 180 solar lights, new perimeter fencing and rehabilitated change rooms with boreholes.",
                'metrics' => [
                    ['value' => 'D100M', 'label' => 'SK West/Africell Mini-Stadium'],
                    ['value' => 'D8M', 'label' => 'Traffic Light Park (Africell)'],
                    ['value' => '180', 'label' => 'Solar Lights, Buffer Zone'],
                ],
            ],
            [
                'key' => 'flood-prevention',
                'tag' => 'Flood Prevention',
                'title' => 'Disaster Mitigation & Flood Prevention',
                'summary' => 'Around D20 million spent over four years clearing drains, excavating canals with NDMA, and removing illegal dumpsites before the rains.',
                'description' => 'Kanifing Municipal Council spends roughly D5 million a year — about D20 million over four years — on flood prevention ahead of the rainy season. Work includes clearing debris from 45 strategic drains and 30 kilometres of drainage annually, an annual excavation of the Kotu/Abuko canal in partnership with the National Disaster Management Agency (NDMA), and the clearance of 40-plus illegal dumpsites a year. The Council also reinstituted "set settal" — community cleaning exercises held across the municipality\'s wards.',
                'metrics' => [
                    ['value' => 'D20M', 'label' => 'Spent Over 4 Years'],
                    ['value' => '30km', 'label' => 'Drains Cleared Annually'],
                    ['value' => '40+', 'label' => 'Illegal Dumpsites Cleared / Year'],
                ],
            ],
            [
                'key' => 'governance-reform',
                'tag' => 'Governance',
                'title' => 'Governance, Housing & Digital Reform',
                'summary' => "A dedicated GIS unit and the municipality's first official map, a tripled corruption-prosecution rate, and a plan for an affordable-housing company.",
                'description' => "The Council established a dedicated GIS unit for addressing and street naming, producing Kanifing's first official municipal map and linking properties to rate data to improve revenue collection. Under improved financial controls, the corruption prosecution rate tripled, alongside staff reforms including a 50% salary increase, more frequent appraisals and a fleet investment of 9 pickups, 6 SUVs and a minibus. On housing, the rent tribunal function was transferred to the Kanifing Magistrate's Court, a 2023 plan was announced to establish an affordable housing company, and 176 plots of land were facilitated for KMC staff.",
                'metrics' => [
                    ['value' => '3x', 'label' => 'Corruption Prosecution Rate'],
                    ['value' => '50%', 'label' => 'Staff Salary Increase'],
                    ['value' => '176', 'label' => 'Staff Housing Plots'],
                ],
            ],
        ];

        foreach ($projects as $i => $data) {
            Project::firstOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['published' => true, 'sort_order' => $i + 1]),
            );
        }
    }
}
