<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();

            $table->boolean('waiver_forze')->default(false);
            $table->longText('waiver_text')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('services_id')->nullable()->index('services_id');
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('suburb')->nullable();
            $table->string('postcode')->nullable();
            $table->string('district')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->unsignedInteger('countries_id')->nullable()->index('countries_id');
            $table->string('city')->nullable();
            $table->string('gmaps')->nullable();
            $table->float('longitude', 13, 10)->nullable();
            $table->float('latitude', 13, 10)->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();

            $table->dateTime('since')->nullable();
            $table->dateTime('until')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->integer('calendar_days')->unsigned()->default(14);

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('pic')->nullable();

            $table->integer('companies_id')->unsigned()->index('companies_id');
            $table->foreign('companies_id')
                ->references('id')->on('companies');
            $table->integer('brands_id')->unsigned()->index('brands_id');
            $table->foreign('brands_id')
                ->references('id')->on('brands');
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
        Schema::dropIfExists('locations');
    }
}
