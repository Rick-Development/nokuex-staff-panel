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
        Schema::create('reconciliation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('reconciliation_type'); // e.g., 'blusalt', 'bank', 'manual'
            $table->date('reconciliation_date');
            $table->decimal('expected_balance', 15, 2);
            $table->decimal('actual_balance', 15, 2);
            $table->decimal('difference', 15, 2);
            $table->enum('status', ['pending', 'matched', 'discrepancy', 'resolved'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['reconciliation_type', 'reconciliation_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reconciliation_logs');
    }
};
