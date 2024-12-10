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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('nickname')->after('plate');
            $table->string('color_hex')->after('color')->nullable();
            $table->string('icon')->after('odometer')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['nickname']);
            $table->dropColumn(['color_hex']);
            $table->dropColumn(['icon']);
        });
    }
};
