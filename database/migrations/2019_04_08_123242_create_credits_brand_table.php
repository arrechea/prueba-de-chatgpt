<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brands_id')->index('brands_id');
            $table->unsignedInteger('companies_id')->index('companies_id');
            $table->unsignedInteger('credits_id')->index('credits_id');

            $table->foreign('brands_id')->references('id')->on('brands');
            $table->foreign('companies_id')->references('id')->on('companies');
            $table->foreign('credits_id')->references('id')->on('credits');

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
        Schema::dropIfExists('credits_brand');
    }
}
