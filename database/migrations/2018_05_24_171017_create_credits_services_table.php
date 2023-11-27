<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('credits_id')->index('credits_id');
            $table->unsignedInteger('services_id')->index('services_id');
            $table->unsignedInteger('credits')->default(1)->index();

            $table->foreign('credits_id')->references('id')->on('credits')->onDelete('cascade');
            $table->foreign('services_id')->references('id')->on('services')->onDelete('cascade');

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
        Schema::dropIfExists('credits_services');
    }
}
