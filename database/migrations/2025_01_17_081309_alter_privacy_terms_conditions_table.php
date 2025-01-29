<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('privacy_terms_conditions', function (Blueprint $table) {
            DB::statement("ALTER TABLE privacy_terms_conditions ALTER COLUMN type TYPE VARCHAR(1)");
        });

        DB::statement("DROP TYPE IF EXISTS privacy_type");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("CREATE TYPE privacy_type AS ENUM ('P', 'T', 'A')");

        Schema::table('privacy_terms_conditions', function (Blueprint $table) {
            DB::statement("ALTER TABLE privacy_terms_conditions ALTER COLUMN type TYPE privacy_type USING (type::privacy_type)");
        });
    }
};
