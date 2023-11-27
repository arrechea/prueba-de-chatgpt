<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('users_profiles_id',false,true)->index();
            $table->integer('users_id',false,true)->index();

            $table->integer('companies_id',false,true)->index();
            $table->integer('brands_id',false,true)->index();
            $table->integer('locations_id',false,true)->index();

            $table->integer('admin_profiles_id',false,true)->nullable();

            $table->integer('users_recurrent_payments_id',false,true)->index();

            $table->morphs('product');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('recurrence_days');


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
        Schema::dropIfExists('subscriptions');
    }
}
