<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Correct company name: "Safari Trading" -> "Safaro Trading". Also drops the
 * 2013 "Founds Safari Trading" timeline entry — the same fact is already
 * covered in the bio paragraph.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'about_page.bio_body',
            fn ($current) => str_replace('Safari Trading', 'Safaro Trading', (string) $current),
        );

        $this->migrator->update('about_page.timeline', function ($current) {
            return collect($current)
                ->reject(fn ($e) => ($e->title ?? null) === 'Founds Safari Trading')
                ->values()
                ->all();
        });
    }

    public function down(): void
    {
        $this->migrator->update(
            'about_page.bio_body',
            fn ($current) => str_replace('Safaro Trading', 'Safari Trading', (string) $current),
        );

        $this->migrator->update('about_page.timeline', function ($current) {
            $entries = collect($current);

            $founds = [
                'year' => '2013',
                'title' => 'Founds Safari Trading',
                'description' => 'Establishes a company producing and marketing hygiene products before moving into local government politics.',
            ];

            $electedIndex = $entries->search(fn ($e) => ($e->title ?? null) === 'Elected Lord Mayor of Kanifing');

            if ($electedIndex === false) {
                return $entries->push($founds)->all();
            }

            $entries->splice($electedIndex, 0, [$founds]);

            return $entries->all();
        });
    }
};
