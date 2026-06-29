<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::truncate();

        $events = [
            [
                'slug'       => 'diaspora-tour-london-2026',
                'title'      => 'Diaspora Tour — London, UK',
                'badge'      => 'diaspora',
                'flag'       => '🇬🇧',
                'date_day'   => '14',
                'date_month' => 'Jul',
                'date_year'  => '2026',
                'js_day'     => 14,
                'js_month'   => 6,
                'location'   => 'Greater London, United Kingdom',
                'venue'      => 'Venue TBC — Greater London',
                'description'      => 'An evening with Talib Bensouda for the UK Gambian community — dialogue, updates from home, and a chance to connect with fellow Gambians.',
                'full_description' => "An unmissable evening for the UK's Gambian community. Join Mayor Talib Bensouda for a town hall-style gathering where you can hear directly from the Mayor about his vision for The Gambia's future, the progress being made on the ground, and how Gambians in the UK can play their part in shaping what comes next.\n\nThe event will feature an open Q&A session with the Mayor, cultural performances, and plenty of time to connect with fellow Gambians in a warm, welcoming environment. Whether you have been following Talib's work for years or are hearing about him for the first time, you are welcome here.\n\nRegistration is free. Doors open at 5:30pm. The programme begins at 6:00pm.",
                'ics_start'  => '20260714T180000Z',
                'ics_end'    => '20260714T220000Z',
                'is_upcoming' => true,
                'sort_order'  => 1,
            ],
            [
                'slug'       => 'diaspora-tour-new-york-2026',
                'title'      => 'Diaspora Tour — New York, USA',
                'badge'      => 'diaspora',
                'flag'       => '🇺🇸',
                'date_day'   => '22',
                'date_month' => 'Jul',
                'date_year'  => '2026',
                'js_day'     => 22,
                'js_month'   => 6,
                'location'   => 'New York City, United States',
                'venue'      => 'Venue TBC — New York City',
                'description'      => 'Meeting with Gambian community groups, youth leaders, and supporters across the New York metropolitan area.',
                'full_description' => "New York City is home to one of the most vibrant and engaged Gambian communities outside The Gambia. Mayor Talib Bensouda is coming to meet you.\n\nThis town hall will bring together community leaders, youth groups, business owners, and everyday Gambians for an open conversation about The Gambia's future. Topics will include the national campaign, economic development, diaspora investment opportunities, and how the New York Gambian community can directly support progress back home.\n\nExpect real talk, honest answers, and a genuine exchange between the Mayor and the community he serves — regardless of where they call home.\n\nRegistration is free. All Gambians and friends of The Gambia are welcome.",
                'ics_start'  => '20260722T180000Z',
                'ics_end'    => '20260722T220000Z',
                'is_upcoming' => true,
                'sort_order'  => 2,
            ],
            [
                'slug'       => 'diaspora-tour-madrid-2026',
                'title'      => 'Diaspora Tour — Madrid, Spain',
                'badge'      => 'diaspora',
                'flag'       => '🇪🇸',
                'date_day'   => '05',
                'date_month' => 'Aug',
                'date_year'  => '2026',
                'js_day'     => 5,
                'js_month'   => 7,
                'location'   => 'Madrid, Spain',
                'venue'      => 'Venue TBC — Madrid',
                'description'      => 'A town hall with Gambians across Spain and Europe, focusing on diaspora investment, remittances, and political participation.',
                'full_description' => "Spain and Europe are home to thousands of Gambians who work hard, send remittances home, and care deeply about their homeland. The Madrid stop of the Diaspora Tour will focus specifically on the economic and political relationship between Gambians abroad and The Gambia.\n\nMayor Talib Bensouda will speak about diaspora investment, the government's plans to make it easier to send money home, and the critical role that Gambians in Europe play in the political future of their country. There will be a dedicated Q&A and networking session after the programme.\n\nIf you are a Gambian living in Spain, Portugal, Italy, or elsewhere in Europe, this event is for you. Registration is free and open to all.",
                'ics_start'  => '20260805T180000Z',
                'ics_end'    => '20260805T220000Z',
                'is_upcoming' => true,
                'sort_order'  => 3,
            ],
            [
                'slug'       => 'annual-party-convention-2026',
                'title'      => 'Annual Party Convention',
                'badge'      => 'gambia',
                'flag'       => '🇬🇲',
                'date_day'   => '20',
                'date_month' => 'Sep',
                'date_year'  => '2026',
                'js_day'     => 20,
                'js_month'   => 8,
                'location'   => 'Banjul, The Gambia',
                'venue'      => 'Venue TBC — Banjul',
                'description'      => "The party's flagship annual convention — manifesto presentation, leadership elections, and a nationwide gathering of members and supporters.",
                'full_description' => "The Annual Party Convention is the most significant gathering in our political calendar. Delegates travel from every region of The Gambia — and from the diaspora — to participate in decisions that shape the party and the nation.\n\nThis year's convention will formally present the party's full national manifesto, hold elections for key party positions, and mark the official launch of the campaign for national leadership. Talib Bensouda will deliver the keynote address, setting out the vision for The Gambia's next chapter.\n\nThe convention is open to registered party members and invited guests. If you would like to attend as a supporter or observer, please register your interest via the link below and our team will be in touch with details.",
                'ics_start'  => '20260920T090000Z',
                'ics_end'    => '20260920T170000Z',
                'is_upcoming' => true,
                'sort_order'  => 4,
            ],
            [
                'slug'       => 'national-campaign-launch-2026',
                'title'      => 'National Campaign Launch',
                'badge'      => 'gambia',
                'flag'       => '🇬🇲',
                'date_day'   => '01',
                'date_month' => 'Nov',
                'date_year'  => '2026',
                'js_day'     => 1,
                'js_month'   => 10,
                'location'   => 'Independence Stadium, Banjul',
                'venue'      => 'Independence Stadium, Banjul',
                'description'      => "The official launch of Talib Bensouda's national campaign — open to all Gambians. Details to be announced.",
                'full_description' => "The National Campaign Launch is a historic moment — the formal beginning of Talib Bensouda's campaign for national leadership. This is not just a political event. It is a gathering of every Gambian who believes that their country can do better and is willing to work for it.\n\nThe event will take place at Independence Stadium in Banjul and will be open to all Gambians — no party membership required. The programme will include a major speech from Talib Bensouda setting out the full vision for The Gambia, live music and cultural performances, and the public unveiling of the campaign manifesto.\n\nThis is the moment the movement becomes a national campaign. Be there.\n\nFull details, including transport arrangements from across the country, will be announced closer to the date. Register your interest now and be the first to receive updates.",
                'ics_start'  => '20261101T100000Z',
                'ics_end'    => '20261101T160000Z',
                'is_upcoming' => true,
                'sort_order'  => 5,
            ],
        ];

        foreach ($events as $data) {
            Event::create($data);
        }
    }
}
