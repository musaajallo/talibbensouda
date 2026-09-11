<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'key' => 'new-castle-county',
                'quote' => 'New Castle County, Delaware declared 8 July "Kanifing Municipal Council Day," and later marked 19 May 2022 as "Lord Mayor Bensouda Day" — recognition announced during a visit by the County Executive.',
                'name' => 'New Castle County, Delaware',
                'role' => 'United States · 2022',
                'featured' => true,
            ],
            [
                'key' => 'gpm',
                'quote' => "KMC holds full membership of the Global Parliament of Mayors and United Cities and Local Governments of Africa, and sits on the Mayors Migration Council via the Africa–Europe Mayors' Dialogue.",
                'name' => 'Global Parliament of Mayors',
                'role' => 'International membership',
                'featured' => false,
            ],
            [
                'key' => 'dubawa',
                'quote' => "A Dubawa fact-check in December 2025 independently verified several of Kanifing Municipal Council's market, road and library projects against public records.",
                'name' => 'Dubawa Fact-Check',
                'role' => 'December 2025',
                'featured' => false,
            ],
            [
                'key' => 'sister-city-freetown',
                'quote' => "Kanifing Municipal Council holds a sister-city partnership with Freetown, Sierra Leone, supporting cooperation between the two West African capitals' local governments.",
                'name' => 'Freetown, Sierra Leone',
                'role' => 'Sister city',
                'featured' => false,
            ],
            [
                'key' => 'sister-city-madison',
                'quote' => 'A sister-city relationship with Madison, USA has supported community economic development in Kanifing, including a donation of 1,000 waste bins.',
                'name' => 'Madison, USA',
                'role' => 'Sister city',
                'featured' => false,
            ],
            [
                'key' => 'twinning-balassagyarmat',
                'quote' => 'A twinning agreement with Balassagyarmat, Hungary focuses on economic, cultural and educational cooperation between the two municipalities.',
                'name' => 'Balassagyarmat, Hungary',
                'role' => 'Twinning agreement',
                'featured' => false,
            ],
            [
                'key' => 'partnership-barcelona',
                'quote' => 'In partnership with Àrea Metropolitana de Barcelona, the Council built a COVID-19-compliant market in the Talinding Ya Fatou Njie ward.',
                'name' => 'Àrea Metropolitana de Barcelona',
                'role' => 'Spain · market partnership',
                'featured' => false,
            ],
            [
                'key' => 'partnership-kaohsiung',
                'quote' => 'A partnership with Kaohsiung City, Taiwan supports cooperation on economic development, trade, arts and culture.',
                'name' => 'Kaohsiung City, Taiwan',
                'role' => 'Trade & culture partnership',
                'featured' => false,
            ],
        ];

        foreach ($testimonials as $i => $data) {
            Testimonial::firstOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['published' => true, 'sort_order' => $i + 1]),
            );
        }
    }
}
