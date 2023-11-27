<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMapsColumnsToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedInteger('meeting_position')->after('rooms_id')->nullable();
            $table->unsignedInteger('maps_id')->after('meeting_position')->nullable();
            $table->unsignedInteger('maps_objects_id')->after('maps_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('meeting_position');
            $table->dropColumn('maps_id');
            $table->dropColumn('maps_objects_id');
        });
    }
}
