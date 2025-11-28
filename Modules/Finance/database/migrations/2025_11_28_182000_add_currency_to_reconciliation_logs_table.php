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
        Schema::table('reconciliation_logs', function (Blueprint $table) {
            $table->string('currency', 10)->after('reconciliation_type')->default('NGN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reconciliation_logs', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
