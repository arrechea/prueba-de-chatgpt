<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('companies_id')->unsigned()->nullable()->index('companies_id');
            $table->integer('brands_id')->unsigned()->nullable()->index('brands_id');
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->boolean('active')->default(0)->index('active');
            $table->string('image')->nullable();
            $table->enum('type', [
                'discount',
                'credits',
                'buy_get',
            ]);
            $table->integer('buy_get_get')->nullable()->unsigned();
            $table->integer('buy_get_buy')->nullable()->unsigned();
            $table->string('code')->nullable()->index('code');
            $table->integer('discount_number')->nullable()->unsigned();
            $table->enum('discount_type', ['price', 'percent'])->nullable();
            $table->integer('credits')->unsigned()->nullable();
            $table->integer('user_limit')->unsigned()->nullable();
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
        Schema::dropIfExists('offers');
    }
}
