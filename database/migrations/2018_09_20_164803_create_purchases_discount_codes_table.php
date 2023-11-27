<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_discount_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_codes_id')->index();
            $table->string('code')->index();
            $table->unsignedInteger('purchases_id')->index();
            $table->boolean('applied')->default(false)->index();

            $table->unsignedInteger('locations_id')->nullable()->index();
            $table->unsignedInteger('brands_id')->nullable()->index();
            $table->unsignedInteger('companies_id')->nullable()->index();

            $table->unsignedInteger('user_profiles_id')->index();
            $table->unsignedInteger('admin_profiles_id')->nullable()->index();
            $table->unsignedInteger('users_id')->index();

            $table->string('short_description')->nullable();

            $table->enum('discount_type',['price', 'percent'])->default('price');
            $table->unsignedInteger('discount_number')->nullable();
            $table->dateTime('discount_from')->nullable();
            $table->dateTime('discount_to')->nullable();

            $table->unsignedInteger('discount_total');
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
        Schema::dropIfExists('purchase_discount_codes');
    }
}
