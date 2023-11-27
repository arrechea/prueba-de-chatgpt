<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLimitReservationsToMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->unsignedInteger('reservations_limit')->after('expiration_days')->nullable();
        });
        Schema::table('users_memberships', function (Blueprint $table) {
            $table->unsignedInteger('reservations_limit')->after('expiration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('reservations_limit');
        });
        Schema::table('users_memberships', function (Blueprint $table) {
            $table->dropColumn('reservations_limit');
        });
    }
}
