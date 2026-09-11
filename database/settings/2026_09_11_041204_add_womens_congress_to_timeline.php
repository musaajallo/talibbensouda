<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Content audit follow-up (docs/Talib Legacy - The People's Mayor.md): the
 * 2026 KMC-hosted Women's Congress wasn't represented anywhere. No exact date
 * is given in the source, so it's added to the About page timeline (which is
 * year-level already) rather than as a dated Event.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('about_page.timeline', function ($current) {
            $entries = collect($current);

            if ($entries->contains(fn ($e) => ($e->title ?? null) === "Hosts a Women's Congress")) {
                return $current;
            }

            $congress = [
                'year' => '2026',
                'title' => "Hosts a Women's Congress",
                'description' => "KMC hosts a Women's Congress at which Bensouda frames women's economic empowerment as central to his national political platform.",
            ];

            $emergesIndex = $entries->search(fn ($e) => str_contains($e->title ?? '', 'UNITE Movement for Change'));

            if ($emergesIndex === false) {
                return $entries->push($congress)->all();
            }

            $entries->splice($emergesIndex, 0, [$congress]);

            return $entries->all();
        });
    }

    public function down(): void
    {
        $this->migrator->update('about_page.timeline', function ($current) {
            return collect($current)
                ->reject(fn ($e) => ($e->title ?? null) === "Hosts a Women's Congress")
                ->values()
                ->all();
        });
    }
};
