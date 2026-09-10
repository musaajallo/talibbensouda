<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("about_page.{$key}", $value);

        $add('hero_eyebrow', 'About Talib');
        $add('hero_title', 'Talib Ahmed Bensouda');
        $add('hero_subtitle', "Lord Mayor of Kanifing Municipal Council.\nElected in 2018 at 31 — the youngest mayor in Gambian history.");

        $add('bio_eyebrow', 'Biography');
        $add('bio_headline', 'From Bakau to City Hall');
        $add('bio_body', implode("\n\n", [
            'Talib Ahmed Bensouda was born in 1986 in Bakau. He is the son of Amie Bensouda, a prominent Gambian lawyer and former Attorney General, and Ahmed Bensouda, a former Permanent Secretary at the Ministry of Finance. His paternal grandfather was a Moroccan trader who settled in The Gambia in 1913.',
            'He earned a Bachelor of Arts in Economics and Communication Technology from the University of Toronto in 2007. His early career was spent in sales, management and insurance in Canada and The Gambia — including a role as Marketing Manager at Takaful Gambia Limited — and in 2013 he founded Safari Trading, a company producing and marketing hygiene products, before entering local government.',
            'In May 2018, at the age of 31, Bensouda was elected Mayor of the Kanifing Municipal Council, defeating Rambo Jatta of the APRC with 29,325 votes to become the youngest mayor in Gambian history. He was re-elected in May 2023, defeating Bakary Badjie of the National People\'s Party. Both campaigns were noted for avoiding partisan attacks and focusing on a development-first manifesto under the slogan "Together for a Better KM."',
            'As Lord Mayor he has overseen Kanifing\'s first municipality-wide waste collection system, a €3 million EU-funded environmental transformation programme, more than 38 kilometres of new municipal roads, and the construction of the Kanifing Municipal Library and Innovation Hub. He has since become a national political figure, campaigning on what he calls "The Transformation Agenda." He is married with two children.',
        ]));
        $add('bio_photo', null);

        $add('timeline_eyebrow', 'The Journey');
        $add('timeline_headline', 'Key Milestones');
        $add('timeline_lead', 'From the University of Toronto to two terms as Lord Mayor of Kanifing.');
        $add('timeline', [
            ['year' => '1986', 'title' => 'Born in Bakau, The Gambia', 'description' => 'Son of Amie Bensouda, former Attorney General, and Ahmed Bensouda, former Permanent Secretary at the Ministry of Finance.'],
            ['year' => '2007', 'title' => 'Graduates from the University of Toronto', 'description' => 'Earns a Bachelor of Arts in Economics and Communication Technology, then works in sales, management and insurance in Canada and The Gambia.'],
            ['year' => '2013', 'title' => 'Founds Safari Trading', 'description' => 'Establishes a company producing and marketing hygiene products before moving into local government politics.'],
            ['year' => '2018', 'title' => 'Elected Lord Mayor of Kanifing', 'description' => 'Wins the KMC mayoral election at 31 with 29,325 votes, defeating Rambo Jatta of the APRC — the youngest mayor in Gambian history.'],
            ['year' => '2019', 'title' => 'Launches the Mbalit Project', 'description' => "Kanifing's first structured, municipality-wide waste collection system — a fully funded D130 million partnership with QGroup."],
            ['year' => '2021', 'title' => 'Begins the Kanifing Environmental Transformation Programme', 'description' => 'Secures a €3 million EU grant, delivered with Peterborough City Council, covering waste management, education and tree planting.'],
            ['year' => '2023', 'title' => 'Re-elected Lord Mayor of Kanifing', 'description' => "Returns to office for a second term, defeating Bakary Badjie of the ruling National People's Party."],
            ['year' => '2024', 'title' => 'Inaugurates the Municipal Library and Innovation Hub', 'description' => 'Opens the D45 million KETP-funded library, career centre and innovation hub; a Local Language Section is added in April 2025.'],
            ['year' => '2025', 'title' => 'Declares candidacy for the UDP flagbearer position', 'description' => "Enters the contest to be the United Democratic Party's presidential flagbearer ahead of the 2026 election."],
            ['year' => '2026', 'title' => 'Emerges as leader of the UNITE Movement for Change', 'description' => 'Begins campaigning nationally on a platform he calls "The Transformation Agenda."'],
        ]);

        $add('values_eyebrow', 'How He Governs');
        $add('values_headline', 'Four Priorities');
        $add('values_lead', "The themes that run through Kanifing Municipal Council's work under Talib Bensouda — in the budget, and on the ground.");
        $add('values', [
            ['title' => 'Delivery', 'description' => 'Roads, markets, clinics, libraries and a waste system that residents can see and use — projects completed, not just announced.'],
            ['title' => 'Accountability', 'description' => 'Stronger financial controls, digital property records, and a Council that publishes what it spends and what it builds.'],
            ['title' => 'Environment', 'description' => "Waste collection, dumpsite remediation, recycling, flood prevention and a plan to plant 190,000 trees across the municipality's 19 wards."],
            ['title' => 'Youth & Women', 'description' => 'A D20 million youth revolving fund, the "Tekki Fii" innovation challenge, skills centres, and financing aimed at women-led enterprise.'],
        ]);

        $add('national_eyebrow', 'National Politics');
        $add('national_headline', "From Kanifing\nto a National Platform");
        $add('national_body', implode("\n\n", [
            'Talib Bensouda served as National Organizing Secretary of the United Democratic Party (UDP). In 2025 he declared his candidacy for the party\'s presidential flagbearer position, and in 2026 he emerged as leader of the opposition UNITE Movement for Change.',
            'He is campaigning nationally on what he calls "The Transformation Agenda" — carrying the record built in Kanifing to a wider argument about how The Gambia is governed.',
        ]));
        $add('national_pillars', [
            'Local government that delivers visible results',
            'Youth employment and enterprise',
            'Environmental and sanitation reform',
            'Transparent, well-run public finances',
        ]);
        $add('national_logo', null);
        $add('national_logo_name', 'The Transformation Agenda');
        $add('national_logo_caption', 'UNITE Movement for Change · 2026');
        $add('national_cta_label', 'See the Record');
        $add('national_cta_url', '/peoples-mayor');

        $add('cta_headline', "See What's Been Built");
        $add('cta_lead', 'The record speaks for itself — waste systems, roads, markets, clinics and a municipal library, delivered across two terms in Kanifing.');
        $add('cta_primary_label', "The People's Mayor");
        $add('cta_primary_url', '/peoples-mayor');
        $add('cta_secondary_label', 'Community Support');
        $add('cta_secondary_url', '/giving-back');
    }
};
