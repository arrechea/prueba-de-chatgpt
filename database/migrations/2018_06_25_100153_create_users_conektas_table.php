<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersConektasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_conektas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('private_key')->index();
            $table->unsignedInteger('users_id')->index();
            $table->unsignedInteger('user_profiles_id')->index();
            $table->string('user_token');

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
        Schema::dropIfExists('users_conektas');
    }
}
