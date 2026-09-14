<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // The featured video (videos/market-event.mp4, or an uploaded/YouTube
        // override) is about a market launch — the quote was describing the
        // unrelated Mbalit waste-collection project.
        $this->migrator->update(
            'home_page.video_quote',
            fn () => "Latrikunda Sabiji Market, rebuilt as a storey building with 100 shops — part of a growing network of new and upgraded markets across Kanifing.",
        );
    }
};
