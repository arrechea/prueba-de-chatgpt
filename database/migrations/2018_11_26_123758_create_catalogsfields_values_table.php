<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsfieldsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_fields_values', function (Blueprint $table) {
            $table->integer('model_id');
            $table->string('table');
            $table->longText('value');

            $table->integer('catalogs_groups_id')->index('catalogs_groups_id')->index();
            $table->integer('catalogs_groups_index');

            $table->integer('catalogs_fields_id')->index('catalogs_fields_id')->index();
            $table->integer('catalogs_fields_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogs_fields_values');
    }
}
