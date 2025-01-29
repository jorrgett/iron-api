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
        DB::statement("CREATE TYPE privacy_type AS ENUM ('P', 'T', 'A')");

        Schema::create('privacy_terms_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE privacy_terms_conditions ALTER COLUMN type TYPE privacy_type USING (type::privacy_type)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('privacy_terms_conditions', function (Blueprint $table) {
            DB::statement("ALTER TABLE privacy_terms_conditions ALTER COLUMN type TYPE VARCHAR(255)");
        });

        Schema::dropIfExists('privacy_terms_conditions');

        DB::statement("DROP TYPE privacy_type");
    }
};
