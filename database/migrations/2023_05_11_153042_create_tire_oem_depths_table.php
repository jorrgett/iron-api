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
        Schema::create('tire_oem_depths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tire_brand_id')->nullable();
            $table->unsignedBigInteger('tire_model_id')->nullable();
            $table->unsignedBigInteger('tire_size_id')->nullable();
            $table->float('otd', 18, 2)->nullable();
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tire_oem_depths');
    }
};
