<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "CREATE TABLE public.vehicle_tire_histories (
            id bigserial NOT NULL,
            vehicle_id int8 NOT NULL,
            service_id int8 NOT NULL,
            service_date date NOT NULL,
            odometer float8 NOT NULL,
            tire_location varchar(50) NOT NULL,
            otd float8 NOT NULL,
            tread_depth float8 NOT NULL,
            mm_consumed float8 NULL,
            performance_index int4 NULL,
            km_traveled int4 NULL,
            km_proyected int4 NULL,
            odometer_estimated int4 NULL,
            safe_depth float8 NULL,
            lifespan_consumed float8 NULL,
            months_between_visits float8 NULL,
            created_at timestamp(0) NULL,
            updated_at timestamp(0) NULL,
            sequence_id int4 NULL,
            prom_performance_index float8 NULL,
            CONSTRAINT vehicle_tire_histories_pkey PRIMARY KEY (id),
            CONSTRAINT vehicle_tire_histories_un UNIQUE (vehicle_id, service_id, tire_location));"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_tire_histories');
    }
};
