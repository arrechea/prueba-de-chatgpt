<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSimultaneousReservationsColumnToBrand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedInteger('simultaneous_reservations')->after('max_waitlist')->default(1);
            $table->string('map_css')->after('simultaneous_reservations')->default('reservation-template.css');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('simultaneous_reservations');
            $table->dropColumn('map_css');
        });
    }
}
