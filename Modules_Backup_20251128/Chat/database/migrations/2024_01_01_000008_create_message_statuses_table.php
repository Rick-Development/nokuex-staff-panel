<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('chat_messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('staffs')->onDelete('cascade');
            $table->enum('status', ['sent', 'delivered', 'read']);
            $table->timestamp('updated_at');
            
            $table->unique(['message_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_statuses');
    }
};