<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersOffersPossibilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_offers_possibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offers_id')->index('offers_id');
            $table->integer('offers_possibilities_id')->index('offers_possibilities_id');
            $table->integer('model_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers_offers_possibilities');
    }
}
