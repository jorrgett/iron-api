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
        Schema::table('service_oil', function (Blueprint $table) {
            $table->integer('oil_quantity')->nullable();
            $table->unsignedBigInteger('filter_brand_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_oil', function (Blueprint $table) {
            $table->dropColumn('oil_quantity');
            $table->dropColumn('filter_brand_id');
        });
    }
};
