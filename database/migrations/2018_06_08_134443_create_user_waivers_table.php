<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_waivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->index()->nullable();
            $table->integer('users_profile_id')->index()->nullable();
            $table->integer('companies_id')->index()->nullable();
            $table->integer('brands_id')->index()->nullable();
            $table->integer('locations_id')->index()->nullable();
            $table->longText('signature');
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
        Schema::dropIfExists('user_waivers');
    }
}
