<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Customers table
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Support tickets table
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->string('priority')->default('medium');
            $table->string('status')->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('staffs')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // Disputes table
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('dispute_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->text('description');
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('staffs')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('customers');
    }
};