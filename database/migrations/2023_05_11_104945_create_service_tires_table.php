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
        Schema::create('service_tires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->unsignedBigInteger('service_id');
            $table->string('location', 50);
            $table->integer('depth');
            $table->integer('starting_pressure');
            $table->integer('finishing_pressure');
            $table->string('dot');
            $table->unsignedBigInteger('tire_brand_id');
            $table->unsignedBigInteger('tire_model_id');
            $table->unsignedBigInteger('tire_size_id');
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tires');
    }
};
