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
            $table->unsignedBigInteger('odoo_id')->nullable()->change();
            $table->string('brand_name')->nullable();
            $table->string('model_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('odoo_id')->nullable(false)->change();
            $table->dropColumn('brand_name');
            $table->dropColumn('model_name');
            $table->dropColumn('user_id');
        });
    }
};
