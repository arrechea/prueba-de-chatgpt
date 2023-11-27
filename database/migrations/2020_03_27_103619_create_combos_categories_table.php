<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombosCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('combos_id');
            $table->unsignedInteger('category_id');

            $table->foreign('combos_id')->references('id')->on('combos')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('user_categories')->onDelete('cascade');
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
        Schema::dropIfExists('combos_categories');
    }
}
