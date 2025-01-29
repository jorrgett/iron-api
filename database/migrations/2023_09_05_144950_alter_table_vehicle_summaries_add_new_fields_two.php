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
        Schema::table('vehicle_summaries', function (Blueprint $table) {
            $table->date('initial_date')->nullable();
            $table->integer('initial_km')->nullable();
            $table->date('last_visit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_summaries', function (Blueprint $table) {
            $table->dropColumn('initial_date');
            $table->dropColumn('initial_km');
            $table->dropColumn('last_visit');
        });
    }
};
