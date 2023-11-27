<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGympassCheckinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gympass_checkin', function (Blueprint $table) {
            $table->increments('id');

            $table->char('status', 30);
            $table->boolean('expired')->default(false);
            $table->unsignedInteger('users_id');
            $table->unsignedInteger('user_profiles_id');
            $table->unsignedInteger('companies_id');
            $table->unsignedInteger('brands_id');
            $table->unsignedInteger('locations_id');
            $table->json('request_data');
            $table->dateTimeTz('request_time');
            $table->json('response_data')->nullable();
            $table->dateTimeTz('response_time')->nullable();
            $table->json('errors')->nullable();
            $table->unsignedInteger('check_in_admin')->nullable();
            $table->json('extra_fields')->nullable();

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
        Schema::dropIfExists('gympass_checkin');
    }
}
