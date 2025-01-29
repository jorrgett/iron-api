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
        DB::statement("CREATE TABLE public.vehicle_tire_summaries (
	        id bigserial NOT NULL,
	        vehicle_id int8 NOT NULL,
	        tire_location varchar(50) NOT NULL,
	        prom_tire_km_month float8 NULL,
	        prom_tire_mm_x_visit float8 NULL,
	        months_to_tire_unsafe float8 NULL,
	        projected_tire_visits float8 NULL,
	        estimated_months_tire_visits float8 NULL,
	        accum_km_traveled float8 NULL,
	        accum_days_total float8 NULL,
	        life_span_consumed float8 NULL,
	        sequence_id int4 NOT NULL,
	        created_at timestamp(0) NULL,
	        updated_at timestamp(0) NULL,
	        CONSTRAINT vehicle_tire_summaries_pkey PRIMARY KEY (id),
	        CONSTRAINT vehicle_tire_summaries_un UNIQUE (vehicle_id, tire_location)
        );
    ");

        DB::statement("ALTER TABLE public.vehicle_tire_summaries ADD CONSTRAINT vehicle_tire_summaries_vehicle_id_foreign FOREIGN KEY (vehicle_id) REFERENCES public.vehicles(odoo_id);
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_tire_summaries');
    }
};
