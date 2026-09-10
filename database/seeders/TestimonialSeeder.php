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
                'quote' => 'New Castle County, Delaware declared 8 July "Kanifing Municipal Council Day," and later marked 19 May 2022 as "Lord Mayor Bensouda Day" — recognition announced during a visit by the County Executive.',
                'name' => 'New Castle County, Delaware',
                'role' => 'United States · 2022',
                'featured' => true,
            ],
            [
                'quote' => "KMC holds full membership of the Global Parliament of Mayors and United Cities and Local Governments of Africa, and sits on the Mayors Migration Council via the Africa–Europe Mayors' Dialogue.",
                'name' => 'Global Parliament of Mayors',
                'role' => 'International membership',
                'featured' => false,
            ],
            [
                'quote' => "A Dubawa fact-check in December 2025 independently verified several of Kanifing Municipal Council's market, road and library projects against public records.",
                'name' => 'Dubawa Fact-Check',
                'role' => 'December 2025',
                'featured' => false,
            ],
        ];

        foreach ($testimonials as $i => $data) {
            Testimonial::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['published' => true, 'sort_order' => $i + 1]),
            );
        }
    }
}
