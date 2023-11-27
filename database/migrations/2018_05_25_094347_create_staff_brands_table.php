<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('staff_id')->index('staff_id');
            $table->unsignedInteger('brands_id')->index('brands_id');

            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');

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
        Schema::dropIfExists('staff_brands');
    }
}
