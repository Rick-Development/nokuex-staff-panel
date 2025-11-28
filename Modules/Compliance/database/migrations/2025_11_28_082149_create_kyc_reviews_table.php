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
        Schema::create('kyc_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->enum('review_type', ['kyc', 'kyb'])->default('kyc');
            $table->enum('status', ['pending', 'in_review', 'approved', 'rejected', 'resubmit_required'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->json('documents_checked')->nullable(); // List of verified documents
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'review_type']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_reviews');
    }
};
