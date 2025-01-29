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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->string('plate',);
            $table->unsignedBigInteger('vehicle_brand_id')->nullable();
            $table->unsignedBigInteger('vehicle_model_id')->nullable();
            $table->dateTime('register_date',)->nullable();
            $table->string('color')->nullable();
            $table->integer('year')->nullable();
            $table->string('transmission')->nullable();
            $table->string('fuel')->nullable();
            $table->double('odometer')->nullable();
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
