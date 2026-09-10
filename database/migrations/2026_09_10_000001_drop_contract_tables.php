<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * The contract / contract-template feature was unfinished scaffolding (stub
 * admin views, no forms, no seed data) and has been removed along with the
 * hand-rolled /admin area. Drop its tables. There was never production data.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_templates');
    }

    public function down(): void
    {
        // One-way: the models, controllers and original migrations are gone.
        // Restore from git history if the feature is ever revived.
    }
};
