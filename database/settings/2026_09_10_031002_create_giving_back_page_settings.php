<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("giving_back_page.{$key}", $value);

        $add('hero_eyebrow', 'Beyond the Big Projects');
        $add('hero_title', 'Community Support');
        $add('hero_subtitle', "The everyday side of the Council's work — ward development funds, support for elders and faith communities, sport, scholarships and women's livelihoods.");

        $add('intro_eyebrow', 'The Approach');
        $add('intro_headline', "Money That Reaches\nthe Ward Level");
        $add('intro_body', "Alongside the large infrastructure projects, Kanifing Municipal Council channels funding directly to its 19 wards — through Ward Development Committees, community heads, faith institutions, youth teams and women's gardens.\n\nEach Ward Development Committee receives an annual development fund, and the Council has opened, furnished and paid rent on a dedicated office in every ward. Community heads (Alkalis) receive stipends, and the Council contributes to mosque refurbishment and religious gatherings each year.");
        $add('intro_quote', '"Together for a Better KM."');
        $add('intro_quote_attribution', '— Kanifing Municipal Council campaign slogan');

        $add('intro_stats', [
            ['value' => 'D200k', 'label' => 'Per ward, per year, to each Ward Development Committee'],
            ['value' => '19', 'label' => 'Ward Development Committee offices opened and equipped'],
            ['value' => 'D6M+', 'label' => 'In scholarships to KM students over six years'],
            ['value' => '155', 'label' => 'Sewing machines distributed to skills and community centres'],
        ]);

        $add('programmes_eyebrow', 'Where It Goes');
        $add('programmes_headline', 'Community Programmes');
        $add('programmes_lead', 'Six strands of direct community investment, drawn from Council records.');

        $add('impact_stats', [
            ['value' => '19', 'suffix' => '', 'label' => 'Ward Development Offices'],
            ['value' => '16', 'suffix' => '', 'label' => 'Alkalis Supported'],
            ['value' => '180', 'suffix' => '', 'label' => 'Solar Lights — Buffer Zone'],
            ['value' => '155', 'suffix' => '', 'label' => 'Sewing Machines Distributed'],
        ]);

        $add('community_eyebrow', 'In the Wards');
        $add('community_headline', 'Community Support in Action');
        $add('community_lead', "Ward offices, women's gardens, community centres and youth pitches across Kanifing.");

        $add('enterprises_eyebrow', "Run at Arm's Length");
        $add('enterprises_headline', 'Municipal Enterprises');
        $add('enterprises_lead', 'The Council set up two limited liability companies to deliver services separately from core administration.');

        $add('enterprises', [
            [
                'title' => 'Kanifing Municipal Transport',
                'body' => 'Kanifing Municipal Transport (KMT) was established to provide affordable, accessible bus services for residents, with provision for the elderly and differently abled.',
                'role' => 'Council-owned company',
            ],
            [
                'title' => 'Kanifing Municipal Markets',
                'body' => "Kanifing Municipal Markets (KMM) develops and manages the municipality's expanding market network, with projects at Faji Kunda, Abuko, Bundung Jola Kunda, Mbar Pa Dembo and Bakoteh.",
                'role' => 'Council-owned company',
            ],
            [
                'title' => 'Municipal Police & By-laws',
                'body' => "Municipal Police grew from 42 to 200 personnel, resourced with new pickups, uniforms, motorbikes and training at the Gambia Police School, alongside KMC's first comprehensive by-laws.",
                'role' => 'Enforcement reform',
            ],
        ]);

        $add('help_eyebrow', 'Get Involved');
        $add('help_headline', 'How You Can Help');
        $add('help_lead', 'Community work is never a one-person job. There are a few ways to be part of it.');

        $add('help_cards', [
            [
                'title' => 'Volunteer Your Time',
                'description' => "Help at a community event, a clean-up or a school drive. Get in touch and the team will point you to where you're needed.",
                'cta_label' => 'Volunteer',
                'cta_url' => '/contact#volunteer',
            ],
            [
                'title' => 'Share the Record',
                'description' => "Tell people what's been built in Kanifing. Point them to the record and let them judge it for themselves.",
                'cta_label' => 'See the Record',
                'cta_url' => '/peoples-mayor',
            ],
            [
                'title' => 'Get in Touch',
                'description' => 'Questions, partnership ideas or press enquiries — send a message and someone from the team will respond.',
                'cta_label' => 'Contact the Team',
                'cta_url' => '/contact',
            ],
        ]);

        $add('cta_headline', 'The Bigger Projects');
        $add('cta_lead', "Ward-level support sits alongside the Council's flagship work on waste, roads, markets, health and the municipal library.");
        $add('cta_primary_label', "The People's Mayor");
        $add('cta_primary_url', '/peoples-mayor');
        $add('cta_secondary_label', 'Get in Touch');
        $add('cta_secondary_url', '/contact');
    }
};
