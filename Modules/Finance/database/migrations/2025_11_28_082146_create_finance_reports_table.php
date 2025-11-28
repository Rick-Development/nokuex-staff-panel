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
        Schema::create('finance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_by')->constrained('staff')->onDelete('cascade');
            $table->string('report_type'); // e.g., 'daily', 'weekly', 'monthly', 'custom'
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('data'); // Report data/metrics
            $table->string('file_path')->nullable(); // Path to exported file
            $table->timestamps();
            
            $table->index(['report_type', 'created_at']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_reports');
    }
};
