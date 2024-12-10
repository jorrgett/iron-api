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
        Schema::table('vehicle_summaries', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->integer('accum_km_traveled')->nullable();
            $table->integer('accum_days_total')->nullable();
            $table->integer('accum_oil_changes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_summaries', function (Blueprint $table) {
            $table->dropColumn('accum_km_traveled');
            $table->dropColumn('accum_days_total');
            $table->dropColumn('accum_oil_changes');
            $table->unsignedBigInteger('service_id');
        });
    }
};
