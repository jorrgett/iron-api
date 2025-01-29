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
        Schema::dropIfExists('topics');

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('service')->default(false);
            $table->string('pct_lower')->nullable();
            $table->string('pct_upper')->nullable();
            $table->boolean('physical_state')->default(false);
            $table->boolean('promotion')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
