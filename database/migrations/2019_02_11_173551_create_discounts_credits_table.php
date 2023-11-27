<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_codes_id')->index('discount_codes_id');
            $table->unsignedInteger('credits_id')->index('credits_id');

            $table->foreign('credits_id')->references('id')->on('credits')->onDelete('cascade');
            $table->foreign('discount_codes_id')->references('id')->on('discount_codes')->onDelete('cascade');

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
        Schema::dropIfExists('discount_codes_credits');
    }
}
