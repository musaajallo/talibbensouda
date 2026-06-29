<?php

use App\Models\ContractTemplate;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContractTemplate::class)->constrained()->restrictOnDelete();

            // Counterparty — keep flexible; could be a User, customer, organisation.
            $table->nullableMorphs('counterparty');

            $table->string('reference')->unique()->comment('Human-readable contract number');
            $table->string('title');

            $table->json('variables')->comment('Per-contract values rendered into the template');

            $table->string('status')->default('draft')->comment('draft, sent, signed, void');

            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip')->nullable();
            $table->text('signed_signature')->nullable()->comment('Base64 PNG or typed name');

            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
