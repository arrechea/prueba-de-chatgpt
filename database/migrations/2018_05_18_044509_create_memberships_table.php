<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('companies_id')->nullable()->index('companies_id');
            $table->integer('brands_id')->nullable()->index('brands_id');
            $table->integer('price')->unsigned()->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('hide_in_home')->default(true);
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->integer('discount_number')->nullable();
            $table->enum('discount_type', ['price', 'percent'])->default('price');
            $table->dateTime('discount_from')->nullable();
            $table->dateTime('discount_to')->nullable();
            $table->integer('expiration_days')->nullable();
            $table->integer('reservations_min')->nullable();
            $table->integer('reservations_max')->nullable();
            $table->integer('meeting_max_reservation')->unsigned()->default(1);
            $table->string('pic')->nullable();
            $table->integer('order')->index('order')->default(0);

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
        Schema::dropIfExists('memberships');
    }
}
