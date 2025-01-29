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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->date('date');
            $table->float('odometer');
            $table->unsignedBigInteger('odometer_id');
            $table->string('state', 15);
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
