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
            $table->dropColumn('oil_change_date');
            $table->dropColumn('oil_change_km');
            $table->dropColumn('oil_change_km_next');
            $table->dropColumn('oil_change_life_span');
            $table->dropColumn('oil_change_life_span_standar');
            $table->date('last_oil_change_date')->nullable();
            $table->integer('last_oil_change_km')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_summaries', function (Blueprint $table) {
            $table->dropColumn('last_oil_change_date');
            $table->dropColumn('last_oil_change_km');
            $table->date('oil_change_date')->nullable();
            $table->integer('oil_change_km')->nullable();
            $table->integer('oil_change_km_next')->nullable();
            $table->integer('oil_change_life_span')->nullable();
            $table->integer('oil_change_life_span_standar')->nullable();
        });
    }
};
