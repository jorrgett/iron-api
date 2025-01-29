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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('legals_accepted')->default(false);

            $table->unsignedBigInteger('terms_and_conditions_id')->nullable();
            $table->unsignedBigInteger('legal_disclaimer_id')->nullable();
            $table->unsignedBigInteger('privacy_policy_id')->nullable();

            $table->foreign('terms_and_conditions_id')->references('id')->on('privacy_terms_conditions')->onDelete('set null');
            $table->foreign('legal_disclaimer_id')->references('id')->on('privacy_terms_conditions')->onDelete('set null');
            $table->foreign('privacy_policy_id')->references('id')->on('privacy_terms_conditions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['terms_and_conditions_id']);
            $table->dropForeign(['legal_disclaimer_id']);
            $table->dropForeign(['privacy_policy_id']);

            $table->dropColumn('terms_and_conditions_id');
            $table->dropColumn('legal_disclaimer_id');
            $table->dropColumn('privacy_policy_id');
            $table->dropColumn('legals_accepted');
        });
    }
};
