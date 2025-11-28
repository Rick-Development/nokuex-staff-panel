<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_channels', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_private');
            $table->string('department')->nullable()->after('type');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::table('chat_channels', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'department']);
        });
    }
};