<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("peoples_mayor_page.{$key}", $value);

        $add('hero_eyebrow', 'The Record · 2018 – 2026');
        $add('hero_title', "The People's Mayor");
        $add('hero_subtitle', "Kanifing Municipal Council's work under Talib Bensouda — waste, roads, markets, health, youth and women.");

        $add('impact_stats', [
            ['value' => '130', 'suffix' => 'M', 'label' => 'Mbalit Waste Project (GMD)'],
            ['value' => '38', 'suffix' => 'km', 'label' => 'New Roads Across 19 Wards'],
            ['value' => '172', 'suffix' => '', 'label' => 'Youth Employed — Mbalit'],
            ['value' => '31,867', 'suffix' => '', 'label' => 'Properties Digitally Addressed'],
        ]);

        $add('pull_quote', 'Council revenue rose from D115 million in 2017 to D332 million in 2022 — and every dalasi of it is meant to show up as something residents can use.');
        $add('pull_quote_attribution', '— Kanifing Municipal Council, 2018–2022 record');

        $add('community_eyebrow', 'On the Ground');
        $add('community_headline', 'Where the Work Happens');
        $add('community_lead', "Kanifing's 19 wards — from Bakau and Bakoteh to Latrikunda, Tallinding and Serrekunda.");

        $add('figures_note', 'Figures are drawn from Kanifing Municipal Council records and public reporting. Council-reported financial figures for 2018–2022 are self-reported; several KMC market, road and library projects were independently verified by a Dubawa fact-check in December 2025.');

        $add('cta_headline', 'The Full Picture');
        $add('cta_lead', "Beyond the headline projects, the Council invests directly in wards, elders, faith communities, sport and women's livelihoods.");
        $add('cta_primary_label', 'Community Support');
        $add('cta_primary_url', '/giving-back');
        $add('cta_secondary_label', 'Events & Milestones');
        $add('cta_secondary_url', '/events');
    }
};
