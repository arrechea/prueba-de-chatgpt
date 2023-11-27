<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('capacity')->unsigned();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('rows')->default(1)->unsigned();
            $table->integer('columns')->default(1)->unsigned();
            $table->string('image_background')->nullable();

            $table->unsignedInteger('locations_id')->index()->nullable();
            $table->unsignedInteger('brands_id')->index()->nullable();
            $table->unsignedInteger('companies_id')->index()->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('maps');
    }
}
