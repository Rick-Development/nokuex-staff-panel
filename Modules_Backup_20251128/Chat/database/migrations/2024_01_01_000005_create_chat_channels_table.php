<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('department'); // department, private, group
            $table->string('department')->nullable(); // which department this channel belongs to
            $table->boolean('is_private')->default(false);
            $table->boolean('is_active')->default(true); // Add this line
            $table->foreignId('created_by')->constrained('staffs')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['type', 'department']);
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_channels');
    }
};