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
        Schema::table('service_tires', function (Blueprint $table) {
            $table->boolean('regular')->default(false);
            $table->boolean('staggered')->default(false);
            $table->boolean('central')->default(false);
            $table->boolean('right_shoulder')->default(false);
            $table->boolean('left_shoulder')->default(false);

            $table->boolean('not_apply')->default(true);
            $table->boolean('bulge')->default(false);
            $table->boolean('perforations')->default(false);
            $table->boolean('vulcanized')->default(false);
            $table->boolean('aging')->default(false);
            $table->boolean('cracked')->default(false);
            $table->boolean('deformations')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_tires', function (Blueprint $table) {
            $table->dropColumn(['not_apply']);
            $table->dropColumn(['bulge']);
            $table->dropColumn(['perforations']);
            $table->dropColumn(['vulcanized']);
            $table->dropColumn(['aging']);
            $table->dropColumn(['cracked']);
            $table->dropColumn(['deformations']);
        });
    }
};
