<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscriptions_id')->index();
            $table->unsignedInteger('users_profiles_id')->index();
            $table->unsignedInteger('users_id')->index();
            $table->unsignedInteger('purchases_id')->nullable()->index();

            $table->unsignedInteger('companies_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('locations_id')->index();

            $table->enum('status', ['complete', 'error', 'incomplete'])->default('incomplete');

            $table->dateTime('completion_time')->nullable();
            $table->dateTime('renewal_time')->nullable();

            $table->boolean('renewed')->default(false);

            $table->longText('error_message')->nullable();

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
        Schema::dropIfExists('subscriptions_payments');
    }
}
