<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compliance_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->string('flag_type'); // e.g., 'suspicious_transaction', 'kyc_expired', 'high_volume'
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['pending', 'under_review', 'cleared', 'escalated'])->default('pending');
            $table->text('description');
            $table->json('metadata')->nullable(); // Additional context data
            $table->text('resolution_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'severity']);
            $table->index('user_id');
            $table->index('flag_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_flags');
    }
};
