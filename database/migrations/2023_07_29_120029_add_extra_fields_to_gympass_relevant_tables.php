<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToGympassRelevantTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('meetings', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('services', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->json('extra_fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
    }
}
