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
        Schema::create('service_configs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 25);
            $table->string('url_base', 150);
            $table->string('app_code', 25);
            $table->string('app_secret', 150);
            $table->string('app_token', 150);
            $table->string('app_json_config', 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_config');
    }
};
