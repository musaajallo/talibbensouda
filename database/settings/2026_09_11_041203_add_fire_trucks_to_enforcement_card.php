<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Content audit follow-up (docs/Talib Legacy - The People's Mayor.md):
 * the "Municipal Police & By-laws" enterprise card was missing the fire truck
 * donation and the planned tow depot.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('giving_back_page.enterprises', function ($current) {
            return collect($current)->map(function ($item) {
                if (($item->title ?? null) === 'Municipal Police & By-laws') {
                    $item->body = "Municipal Police grew from 42 to 200 personnel, resourced with new pickups, uniforms, motorbikes and training at the Gambia Police School, alongside KMC's first comprehensive by-laws. The Council also donated 5 fire trucks to the Gambia Fire and Rescue Services in 2022, and has planned a dedicated vehicle towing operation with its own tow depot.";
                }

                return $item;
            })->all();
        });
    }

    public function down(): void
    {
        $this->migrator->update('giving_back_page.enterprises', function ($current) {
            return collect($current)->map(function ($item) {
                if (($item->title ?? null) === 'Municipal Police & By-laws') {
                    $item->body = "Municipal Police grew from 42 to 200 personnel, resourced with new pickups, uniforms, motorbikes and training at the Gambia Police School, alongside KMC's first comprehensive by-laws.";
                }

                return $item;
            })->all();
        });
    }
};
