<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Leads table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->foreignId('assigned_to')->constrained('staffs')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Follow-ups table
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('type')->default('call');
            $table->timestamps();
        });

        // Sales performance table
        Schema::create('sales_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->date('period');
            $table->integer('leads_generated')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('target', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_performance');
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('leads');
    }
};