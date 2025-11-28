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
        Schema::create('staff_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('action'); // e.g., 'login', 'update_user', 'freeze_account'
            $table->string('module')->nullable(); // e.g., 'Core', 'CustomerCare', 'Compliance'
            $table->string('entity_type')->nullable(); // e.g., 'User', 'SupportTicket'
            $table->unsignedBigInteger('entity_id')->nullable(); // ID of the affected entity
            $table->json('old_values')->nullable(); // Previous state
            $table->json('new_values')->nullable(); // New state
            $table->text('description')->nullable(); // Human-readable description
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['staff_id', 'created_at']);
            $table->index('action');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_audit_logs');
    }
};
