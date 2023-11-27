<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('staff_id')->index('staff_id');
            $table->unsignedInteger('brands_id')->index('brands_id');
            $table->unsignedInteger('locations_id')->index('locations_id');

            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_locations');

    }

}
