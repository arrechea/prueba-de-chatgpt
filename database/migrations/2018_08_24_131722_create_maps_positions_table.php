<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapsPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('image')->nullable();
            $table->string('image_selected')->nullable();
            $table->string('image_disabled')->nullable();
            $table->enum('type',['private','public'])->default('public');
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('maps_positions');
    }
}
