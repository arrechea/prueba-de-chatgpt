<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsFieldsControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_groups_controls', function (Blueprint $table) {
            $table->unsignedInteger('catalogs_groups_id')->index('catalogs_groups_id');
            $table->enum('section', ['register', 'reservations_list', 'profile'])->index('section');

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
        Schema::dropIfExists('catalogs_groups_controls');
    }
}
