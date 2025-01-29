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
        DB::statement("CREATE TABLE public.oil_change_histories (
            id bigserial NOT NULL,
            vehicle_id int8 NOT NULL,
            service_id int8 NOT NULL,
            service_state varchar(15) NULL,
            change_date date NULL,
            change_km int4 NULL,
            change_next_km int4 NULL,
            change_next_date date NULL,
            life_span int4 NULL,
            life_span_standar int4 NULL,
            created_at timestamp NULL,
            updated_at timestamp NULL,
            sequence_id int8 NULL,
            CONSTRAINT oil_change_histories_pk PRIMARY KEY (vehicle_id, service_id)
        );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_change_histories');
    }
};
