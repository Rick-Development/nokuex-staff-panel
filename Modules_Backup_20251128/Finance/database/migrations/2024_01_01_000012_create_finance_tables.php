<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Transactions table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('type'); // deposit, withdrawal, transfer, etc.
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Blusalt OTC transactions table
        Schema::create('blusalt_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('blusalt_reference')->unique();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->json('blusalt_response')->nullable();
            $table->timestamps();
        });

        // Reconciliation table
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->date('reconciliation_date');
            $table->decimal('expected_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('variance', 15, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->constrained('staffs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reconciliations');
        Schema::dropIfExists('blusalt_transactions');
        Schema::dropIfExists('transactions');
    }
};