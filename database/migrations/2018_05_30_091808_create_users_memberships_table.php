<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_memberships', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchases_id')->index()->nullable();
            $table->unsignedInteger('purchase_items_id')->index()->nullable();

            $table->unsignedInteger('user_profiles_id')->index();
            $table->unsignedInteger('users_id')->index();
            $table->unsignedInteger('memberships_id')->index();

            $table->dateTime('expiration_date')->index();

            $table->unsignedInteger('locations_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('companies_id')->index();


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
        Schema::dropIfExists('users_memberships');
    }
}
