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
        Schema::table('service_balancing', function (Blueprint $table) {
            $table->boolean('wheel_good_state')->default(false);
            $table->boolean('wheel_scratched')->default(false);
            $table->boolean('wheel_cracked')->default(false);
            $table->boolean('wheel_bent')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_balancing', function (Blueprint $table) {
            $table->dropColumn(['wheel_good_state']);
            $table->dropColumn(['wheel_scratched']);
            $table->dropColumn(['wheel_cracked']);
            $table->dropColumn(['wheel_bent']);
        });
    }
};
