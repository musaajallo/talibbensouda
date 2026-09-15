<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Supports emailing someone a link to submit their own testimonial. `approved`
 * gates whether the submitter can still edit their own row (see
 * Testimonial::canBeEditedBySubmitter()) and is separate from `published`,
 * which — as before — controls public visibility. Approving something never
 * publishes it; that's still a deliberate, separate step.
 *
 * Existing rows were all entered directly by an admin, so they default to
 * already approved.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->boolean('approved')->default(true)->after('published');
            $table->string('invite_token', 64)->nullable()->unique()->after('sort_order');
            $table->string('invite_name')->nullable()->after('invite_token');
            $table->string('invite_email')->nullable()->after('invite_name');
            $table->timestamp('invite_sent_at')->nullable()->after('invite_email');
            $table->timestamp('submitted_at')->nullable()->after('invite_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropColumn(['approved', 'invite_token', 'invite_name', 'invite_email', 'invite_sent_at', 'submitted_at']);
        });
    }
};
