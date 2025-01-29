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
        Schema::create('vehicle_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->unique();
            $table->unsignedBigInteger('service_id');
            $table->integer('prom_km_month')->nullable();
            $table->integer('visits_number')->nullable();
            $table->date('oil_change_date')->nullable();
            $table->integer('oil_change_km')->nullable();
            $table->integer('oil_change_km_next')->nullable();
            $table->integer('oil_change_life_span')->nullable();
            $table->integer('oil_change_life_span_standar')->nullable();
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_summaries');
    }
};
