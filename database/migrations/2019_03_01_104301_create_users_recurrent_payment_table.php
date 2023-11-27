<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRecurrentPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_recurrent_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_types_id')->index();
            $table->unsignedInteger('users_id')->index();
            $table->unsignedInteger('users_profiles_id')->index();

            $table->unsignedInteger('companies_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('locations_id')->index();

            $table->json('payment_data')->nullable();

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
        Schema::dropIfExists('users_recurrent_payment');
    }
}
