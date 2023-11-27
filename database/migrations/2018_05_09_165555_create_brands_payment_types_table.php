<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsPaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands_payment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brands_id')->index('brands_id');
            $table->unsignedInteger('payment_types_id')->index('payment_types_id');
            $table->json('config')->nullable();
            $table->boolean('front')->default(false);
            $table->boolean('back')->default(false);

            $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('payment_types_id')->references('id')->on('payment_types')->onDelete('cascade');

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
        Schema::dropIfExists('brands_payment_types');
    }
}
