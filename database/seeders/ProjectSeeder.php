<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * The six project cards that previously lived as a hardcoded array in
 * welcome.blade.php (and, in expanded form, peoples-mayor.blade.php).
 * Metrics are backfilled from the People's Mayor page.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'tag' => 'Waste',
                'title' => 'The Mbalit Project',
                'description' => "Kanifing's first municipality-wide waste collection system — a fully funded D130 million partnership with 24 new compactor trucks and 172 youth jobs.",
                'metrics' => [
                    ['value' => 'D130M', 'label' => 'Project value'],
                    ['value' => '24', 'label' => 'Compactor trucks'],
                    ['value' => '172', 'label' => 'Youth employed'],
                ],
            ],
            [
                'tag' => 'Environment',
                'title' => 'Environmental Transformation',
                'description' => 'A €3 million EU-funded programme covering waste, education and tree planting — including the plan to plant 190,000 trees across 19 wards.',
                'metrics' => [
                    ['value' => '€3M', 'label' => 'EU grant'],
                    ['value' => '190,000', 'label' => 'Trees planned'],
                    ['value' => '19', 'label' => 'Wards covered'],
                ],
            ],
            [
                'tag' => 'Roads',
                'title' => 'Road Network Project',
                'description' => 'More than D300 million of Council funding for 38 kilometres of new roads, 11 feeder roads and two bridges connecting all 19 wards.',
                'metrics' => [
                    ['value' => '38km', 'label' => 'New roads'],
                    ['value' => '11', 'label' => 'Feeder roads'],
                    ['value' => 'D300M+', 'label' => 'Council funding'],
                ],
            ],
            [
                'tag' => 'Markets',
                'title' => 'Markets Rebuilt',
                'description' => "Latrikunda Sabiji rebuilt with 100 shops; Serrekunda Market upgraded with a women's shed, CCTV, boreholes and security lighting.",
                'metrics' => [
                    ['value' => '100', 'label' => 'New shops, Latrikunda'],
                    ['value' => '2', 'label' => 'Markets rebuilt / upgraded'],
                ],
            ],
            [
                'tag' => 'Health',
                'title' => 'Healthcare Access',
                'description' => 'Nine ambulances for nine community clinics, a D15 million expansion of the Bundung Maternity Ward, and support for local clinics.',
                'metrics' => [
                    ['value' => '9', 'label' => 'Ambulances'],
                    ['value' => 'D15M', 'label' => 'Maternity ward expansion'],
                ],
            ],
            [
                'tag' => 'Jobs',
                'title' => 'Jobs, Skills & Enterprise',
                'description' => 'A D20 million youth revolving fund, the "Tekki Fii" innovation challenge, skills centres and 155 sewing machines for community centres.',
                'metrics' => [
                    ['value' => 'D20M', 'label' => 'Youth revolving fund'],
                    ['value' => '155', 'label' => 'Sewing machines'],
                ],
            ],
        ];

        foreach ($projects as $i => $data) {
            Project::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['published' => true, 'sort_order' => $i + 1]),
            );
        }
    }
}
