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
        Schema::create('account_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->enum('action_type', ['freeze', 'unfreeze', 'suspend', 'activate', 'restrict'])->default('freeze');
            $table->text('reason');
            $table->text('internal_notes')->nullable();
            $table->timestamp('action_expires_at')->nullable(); // For temporary actions
            $table->boolean('is_active')->default(true);
            $table->foreignId('reversed_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['action_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_actions');
    }
};
