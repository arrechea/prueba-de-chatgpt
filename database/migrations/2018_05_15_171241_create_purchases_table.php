<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('payment_types_id')->nullable()->index();
            $table->string('payment_data_id')->nullable()->index();
            $table->unsignedInteger('currencies_id')->index();
            $table->enum('status', ['pending', 'complete'])->default('pending')->index();

            $table->unsignedInteger('locations_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('companies_id')->index();

            $table->unsignedInteger('user_profiles_id')->index();
            $table->unsignedInteger('admin_profiles_id')->nullable()->index();
            $table->unsignedInteger('users_id')->index();

            $table->float('subtotal')->default(0);
            $table->float('iva')->default(0);
            $table->float('total')->default(0);

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
        Schema::dropIfExists('purchases');
    }
}
