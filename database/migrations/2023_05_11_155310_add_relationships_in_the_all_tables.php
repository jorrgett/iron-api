<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('product_category_id')
                ->references('odoo_id')
                ->on('product_categories');
        });

        Schema::table('tire_models', function (Blueprint $table) {
            $table->foreign('tire_brand_id')
                ->references('odoo_id')
                ->on('tire_brands');
        });

        Schema::table('tire_oem_depths', function (Blueprint $table) {
            $table->foreign('tire_brand_id')
                ->references('odoo_id')
                ->on('tire_brands');

            $table->foreign('tire_model_id')
                ->references('odoo_id')
                ->on('tire_models');

            $table->foreign('tire_size_id')
                ->references('odoo_id')
                ->on('tire_sizes');
        });

        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->foreign('vehicle_brand_id')
                ->references('odoo_id')
                ->on('vehicle_brands');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('vehicle_brand_id')
                ->references('odoo_id')
                ->on('vehicle_brands');

            $table->foreign('vehicle_model_id')
                ->references('odoo_id')
                ->on('vehicle_models');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('store_id')
                ->references('odoo_id')
                ->on('stores');

            $table->foreign('vehicle_id')
                ->references('odoo_id')
                ->on('vehicles');

            $table->foreign('odometer_id')
                ->references('odoo_id')
                ->on('odometers');
        });

        Schema::table('service_items', function (Blueprint $table) {
            $table->foreign('service_id')
                ->references('odoo_id')
                ->on('services');
        });

        Schema::table('service_tires', function (Blueprint $table) {
            $table->foreign('service_id')
                ->references('odoo_id')
                ->on('services');

            $table->foreign('tire_brand_id')
                ->references('odoo_id')
                ->on('tire_brands');

            $table->foreign('tire_model_id')
                ->references('odoo_id')
                ->on('tire_models');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('action_id')
                ->references('id')
                ->on('actions');

            $table->foreign('parent_id')
                ->references('id')
                ->on('categories');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
        });

        Schema::table('tire_models', function (Blueprint $table) {
            $table->dropForeign(['tire_brand_id']);
        });

        Schema::table('tire_oem_depths', function (Blueprint $table) {
            $table->dropForeign(['tire_brand_id']);
            $table->dropForeign(['tire_model_id']);
            $table->dropForeign(['tire_size_id']);
        });

        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->dropForeign(['vehicle_brand_id']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['vehicle_brand_id']);
            $table->dropForeign(['vehicle_model_id']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['odometer_id']);
        });

        Schema::table('service_items', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
        });

        Schema::table('service_tires', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['tire_brand_id']);
            $table->dropForeign(['tire_model_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['action_id']);
            $table->dropForeign(['parent_id']);
        });
    }
};
