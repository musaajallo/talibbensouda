<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("home_page.{$key}", $value);

        // Hero
        $add('hero_eyebrow', 'Lord Mayor of Kanifing');
        $add('hero_headline', "A record of delivery\nfor The Gambia");
        $add('hero_emphasis', 'delivery');
        $add('hero_lead', 'Since 2018, Kanifing Municipal Council under Talib Bensouda has built a municipality-wide waste system, dozens of kilometres of new roads, markets, clinics and a public library. This is the record.');
        $add('hero_primary_label', 'See the Record');
        $add('hero_primary_url', '/peoples-mayor');
        $add('hero_secondary_label', 'About Talib');
        $add('hero_secondary_url', '/about');
        $add('hero_slides', []);

        // Stats bar
        $add('stats', [
            ['value' => '24', 'suffix' => '', 'label' => 'Compactor Trucks Procured'],
            ['value' => '38', 'suffix' => 'km', 'label' => 'New Municipal Roads'],
            ['value' => '9', 'suffix' => '', 'label' => 'Ambulances to Clinics'],
            ['value' => '200', 'suffix' => '%', 'label' => 'Revenue Growth, 2017–22'],
        ]);

        // About teaser
        $add('about_eyebrow', 'About Talib');
        $add('about_headline', "Elected at 31.\nThe youngest mayor\nin Gambian history.");
        $add('about_body', 'Talib Ahmed Bensouda was born in Bakau in 1986 and studied Economics and Communication Technology at the University of Toronto. He worked in business before entering local government, and in May 2018 was elected Mayor of Kanifing Municipal Council, defeating the APRC candidate with 29,325 votes. He was re-elected in 2023.');
        $add('about_cta_label', 'Read Full Bio');

        // Featured video
        $add('video_eyebrow', 'In Focus');
        $add('video_headline', '"Together for a Better KM"');
        $add('video_body', "Both of Talib Bensouda's mayoral campaigns were run on a development-first manifesto and were noted for avoiding partisan attacks. The slogan became the framework for two terms of work across Kanifing's 19 wards.");
        $add('video_quote', "Kanifing's first structured, municipality-wide waste collection system — and the first of its kind in the sub-region.");
        $add('video_youtube_id', '');

        // Section headers
        $add('projects_eyebrow', 'The Record');
        $add('projects_headline', "The People's Mayor");
        $add('projects_lead', "Six areas where Kanifing Municipal Council's work under Talib Bensouda is most visible.");
        $add('projects_cta_label', 'See All Projects');

        $add('community_eyebrow', 'On the Ground');
        $add('community_headline', 'Across the Municipality');
        $add('community_lead', "Openings, launches and community work across Kanifing's 19 wards — from Bakau and Bakoteh to Latrikunda, Tallinding and Serrekunda.");

        $add('recognition_eyebrow', 'Recognition');
        $add('recognition_headline', 'Noted Beyond The Gambia');
        $add('recognition_lead', "Kanifing's work has drawn recognition and partnerships from cities and institutions around the world.");

        $add('milestones_eyebrow', 'Recently');
        $add('milestones_headline', 'Recent Milestones');
        $add('milestones_lead', 'The latest openings and launches across the municipality.');
        $add('milestones_cta_label', 'Events & Milestones');

        // CTA banner
        $add('cta_headline', 'Follow the Work');
        $add('cta_lead', 'Explore the full record from two terms in Kanifing, or get in touch with the team directly.');
        $add('cta_primary_label', 'See the Record');
        $add('cta_primary_url', '/peoples-mayor');
        $add('cta_secondary_label', 'Get in Touch');
        $add('cta_secondary_url', '/contact');
    }
};
