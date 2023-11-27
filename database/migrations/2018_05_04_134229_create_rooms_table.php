<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('details', ['quantity', 'map']);
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('companies_id')->index('companies_id');
            $table->unsignedInteger('brands_id')->index('brands_id');
            $table->unsignedInteger('locations_id')->index('locations_id');
            $table->string('pic')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');

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
        Schema::dropIfExists('rooms');
    }
}
