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
        DB::statement("
            CREATE SEQUENCE inspection_fluid_sequence
            START WITH 1
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            CACHE 1
        ");

        Schema::create('inspection_fluids', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sequence_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->string('transmission_case_oil');
            $table->string('transfer_oil');
            $table->string('gear_box_oil');
            $table->string('engine_coolant');
            $table->string('brake_fluid');
            $table->string('engine_oil');
            $table->string('brake_league');
            $table->string('cleaning_liquid');
            $table->string('fuel_tank');
            $table->string('steering_oil');
            $table->string('front_diff_oil');
            $table->string('rear_diff_oil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        DB::statement("DROP SEQUENCE IF EXISTS inspection_fluid_sequence");
        Schema::dropIfExists('inspection_fluids');
    }
};
