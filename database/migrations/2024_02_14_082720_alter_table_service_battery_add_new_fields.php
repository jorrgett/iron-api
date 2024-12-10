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
        Schema::table('service_battery', function (Blueprint $table) {
            $table->float('starting_current')->nullable();
            $table->float('accumulated_load_capacity')->nullable();
            $table->string('health_status', 100)->nullable();
            $table->float('health_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_battery', function (Blueprint $table) {
            $table->dropColumn(['starting_current']);
            $table->dropColumn(['accumulated_load_capacity']);
            $table->dropColumn(['health_status']);
            $table->dropColumn(['health_percentage']);
        });
    }
};
