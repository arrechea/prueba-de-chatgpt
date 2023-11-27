<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('picture')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->unsignedInteger('locations_id')->index()->nullable();
            $table->unsignedInteger('brands_id')->index()->nullable();
            $table->unsignedInteger('companies_id')->index()->nullable();

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
        Schema::dropIfExists('credits');
    }
}
