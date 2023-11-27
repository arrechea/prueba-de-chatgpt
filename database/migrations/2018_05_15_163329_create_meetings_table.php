<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('companies_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('locations_id')->index();
            $table->unsignedInteger('rooms_id')->index();
            $table->unsignedInteger('staff_id')->index();
            $table->unsignedInteger('services_id')->index();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->enum('details', ['quantity', 'map'])->default('quantity');
            $table->unsignedInteger('capacity')->default(0);

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
        Schema::dropIfExists('meetings');
    }
}
