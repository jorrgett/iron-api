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
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('display_name');
            $table->decimal('qty');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->timestamps();
            $table->unsignedInteger('sequence_id');
            $table->unique(['odoo_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_items');
    }
};
