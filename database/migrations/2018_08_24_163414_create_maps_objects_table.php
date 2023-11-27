<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapsObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps_objects', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('maps_id')->unsigned();
            $table->integer('maps_positions_id')->unsigned();

            $table->integer('position_column')->unsigned();
            $table->integer('position_row')->unsigned();
            $table->integer('position_deep')->unsigned()->default(1);
            $table->integer('position_number')->unsigned()->nullable();

            $table->integer('width')->default(1)->unsigned();
            $table->integer('height')->default(1)->unsigned();

            $table->unsignedInteger('locations_id')->index()->nullable();
            $table->unsignedInteger('brands_id')->index()->nullable();
            $table->unsignedInteger('companies_id')->index()->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maps_objects');
    }
}
