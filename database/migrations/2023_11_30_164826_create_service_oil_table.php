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
        Schema::create('service_oil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('tire_brand_id')->nullable();
            $table->string('oil_viscosity')->nullable();
            $table->string('type_oil')->nullable();
            $table->integer('life_span')->nullable();
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_oil');
    }
};
