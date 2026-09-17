<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Adds a `category` key to each existing timeline entry, driving which icon
 * the milestone grid shows (see AboutPageSettings::TIMELINE_CATEGORIES and
 * resources/views/about.blade.php). Matched by exact title text rather than
 * array position, since position isn't guaranteed to be stable.
 *
 * Note: the migrator's update() callback receives stdClass objects for
 * array-of-object settings properties, not associative arrays — see
 * CLAUDE.md's note on this under "Editing an existing settings array in a
 * migration".
 */
return new class extends SettingsMigration
{
    /** @var array<string, string> */
    protected array $categoryByTitle = [
        'Born in Bakau, The Gambia' => 'personal',
        'Graduates from the University of Toronto' => 'education',
        'Elected Lord Mayor of Kanifing' => 'election',
        'Launches the Mbalit Project' => 'project',
        'Begins the Kanifing Environmental Transformation Programme' => 'environment',
        'Re-elected Lord Mayor of Kanifing' => 'election',
        'Inaugurates the Municipal Library and Innovation Hub' => 'project',
        'Declares candidacy for the UDP flagbearer position' => 'national',
        "Hosts a Women's Congress" => 'national',
        'Emerges as leader of the UNITE Movement for Change' => 'national',
    ];

    public function up(): void
    {
        $this->migrator->update('about_page.timeline', function ($current) {
            return collect($current)->map(function ($item) {
                if (! isset($item->category)) {
                    $item->category = $this->categoryByTitle[$item->title ?? ''] ?? 'project';
                }

                return $item;
            })->all();
        });
    }

    public function down(): void
    {
        $this->migrator->update('about_page.timeline', function ($current) {
            return collect($current)->map(function ($item) {
                unset($item->category);

                return $item;
            })->all();
        });
    }
};
