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
        Schema::create('service_battery', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sequence_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->unsignedBigInteger('battery_brand_id')->nullable();
            $table->unsignedBigInteger('battery_model_id')->nullable();
            $table->timestamp('date_of_purchase')->nullable();
            $table->string('serial_product')->nullable();
            $table->timestamp('warranty_date')->nullable();
            $table->string('amperage')->nullable();
            $table->float('alternator_voltage');
            $table->float('battery_voltage');
            $table->string('status_battery');
            $table->string('status_alternator');
            $table->boolean('good_condition')->default(false);
            $table->boolean('liquid_leakage')->default(false);
            $table->boolean('corroded_terminals')->default(false);
            $table->boolean('frayed_cables')->default(false);
            $table->boolean('inflated')->default(false);
            $table->boolean('cracked_case')->default(false);
            $table->boolean('new_battery')->default(false);
            $table->boolean('replaced_battery')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_battery');
    }
};
