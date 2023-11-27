<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsFieldsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_fields_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->integer('catalogs_id')->index('catalogs_id');
            $table->integer('catalogs_groups_id')->index('catalogs_groups_id');
            $table->integer('catalogs_fields_id')->index('catalogs_fields_id');
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
        Schema::dropIfExists('catalogs_fields_options');
    }
}
