<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('channel_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained('chat_channels')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->boolean('is_admin')->default(false);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['channel_id', 'staff_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_members');
    }
};