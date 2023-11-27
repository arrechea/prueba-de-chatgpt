<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('companies_id')->unsigned()->index('companies_id');
            $table->integer('year')->unsigned()->index('year');
            $table->integer('month')->unsigned()->index('month');
            $table->integer('month_requests')->unsigned();

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
        Schema::dropIfExists('companies_requests');
    }
}
