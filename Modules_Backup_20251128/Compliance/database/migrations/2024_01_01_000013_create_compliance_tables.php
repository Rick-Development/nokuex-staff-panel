<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Account freeze/unfreeze table
        Schema::create('account_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('action'); // freeze, unfreeze
            $table->text('reason');
            $table->foreignId('initiated_by')->constrained('staffs')->onDelete('cascade');
            $table->timestamp('effective_until')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // OTC trade monitoring table
        Schema::create('otc_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->string('type'); // buy, sell
            $table->string('status')->default('pending_review');
            $table->foreignId('reviewed_by')->nullable()->constrained('staffs')->onDelete('set null');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // KYC cases table
        Schema::create('kyc_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('case_type')->default('individual');
            $table->string('status')->default('pending');
            $table->json('documents')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('staffs')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // KYB cases table
        Schema::create('kyb_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('registration_number')->nullable();
            $table->string('status')->default('pending');
            $table->json('documents')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('staffs')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Flagging system table
        Schema::create('compliance_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('flag_type');
            $table->text('reason');
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('active');
            $table->foreignId('flagged_by')->constrained('staffs')->onDelete('cascade');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_flags');
        Schema::dropIfExists('kyb_cases');
        Schema::dropIfExists('kyc_cases');
        Schema::dropIfExists('otc_trades');
        Schema::dropIfExists('account_actions');
    }
};