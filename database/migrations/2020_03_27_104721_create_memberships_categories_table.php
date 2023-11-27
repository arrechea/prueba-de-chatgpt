<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('memberships_id');
            $table->unsignedInteger('category_id');

            $table->foreign('memberships_id')->references('id')->on('memberships')->onDelete('cascade');
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
        Schema::dropIfExists('memberships_categories');
    }
}
