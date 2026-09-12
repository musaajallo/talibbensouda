<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fills in the Tekki Fii sub-detail from the content brief that was missing
 * from the 'jobs' project: the first COVID-response cohort's named winners
 * and amounts, and the separate "Andandorr–Tekki Fii" fund. ProjectSeeder's
 * firstOrCreate never touches an existing row, so this corrects installs
 * that already seeded the old, shorter description.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('projects')->where('key', 'jobs')->update([
            'description' => "A D20 million Youth Revolving Fund provides soft loans to young entrepreneurs. The Mayor's \"Tekki Fii\" Innovative Challenge, run with the EU and the Youth Empowerment Project, has awarded startup grants to Gambian innovators — its first COVID-19-response cohort (June 2020) backed three winners: Gisqo (D313,000), Outboost Media and Analytics (D429,000) and Le Jumbo (D257,500). A further D1 million \"Andandorr–Tekki Fii\" fund, run with YEP (International Trade Centre) and the Gambia Chamber of Commerce and Industry, supports young innovative entrepreneurs. The Bakoteh Production and Innovation Centre, opened in December 2022, supports training and production in textiles and hand-woven goods, and 155 sewing machines were distributed to skills and community centres — alongside financing and cold-storage facilities aimed at women-led enterprise.",
        ]);
    }

    public function down(): void
    {
        DB::table('projects')->where('key', 'jobs')->update([
            'description' => "A D20 million Youth Revolving Fund provides soft loans to young entrepreneurs. The Mayor's \"Tekki Fii\" Innovative Challenge, run with the EU and the Youth Empowerment Project, has awarded startup grants to Gambian innovators. The Bakoteh Production and Innovation Centre, opened in December 2022, supports training and production in textiles and hand-woven goods, and 155 sewing machines were distributed to skills and community centres — alongside financing and cold-storage facilities aimed at women-led enterprise.",
        ]);
    }
};
