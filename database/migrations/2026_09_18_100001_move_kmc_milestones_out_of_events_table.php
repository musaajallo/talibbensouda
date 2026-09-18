<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * These six rows were seeded into `events` before Events and Milestones were
 * split into separate resources. `MilestoneSeeder` recreates them as
 * Milestones; this deletes the originals so they don't also show up as
 * (long-past) Events. Safe to run whether or not the old EventSeeder rows
 * ever made it to this database — deletes are keyed on slug and no-op if the
 * row isn't there.
 */
return new class extends Migration
{
    protected array $slugs = [
        'bakoteh-production-innovation-centre-2022',
        'kanifing-municipal-library-innovation-hub-2024',
        'library-local-language-section-2025',
        'bakau-multipurpose-facility-2025',
        'un-deputy-secretary-general-visit-2025',
        'digital-addresses-policymaking-2026',
    ];

    public function up(): void
    {
        DB::table('events')->whereIn('slug', $this->slugs)->delete();
    }

    public function down(): void
    {
        // Data-only cleanup — MilestoneSeeder now owns this content, and the
        // original Event rows carried fields (badge, ics_start, full_description)
        // that aren't reconstructable from the Milestone they became.
    }
};
