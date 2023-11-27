<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaitlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waitlist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('users_id')->index();
            $table->unsignedInteger('user_profiles_id')->index();
            $table->unsignedInteger('meetings_id')->index();
            $table->dateTime('meeting_start')->nullable();
            $table->dateTime('cancelation_dead_line')->nullable();
            $table->unsignedInteger('reservations_id')->index()->nullable();
            $table->unsignedInteger('staff_id')->index()->nullable();
            $table->unsignedInteger('buyer_staff_id')->index()->nullable();

            $table->unsignedInteger('rooms_id')->index();
            $table->unsignedInteger('locations_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('companies_id')->index();

            $table->unsignedInteger('services_id')->nullable()->index();
            $table->unsignedInteger('memberships_id')->nullable()->index();
            $table->unsignedInteger('credits_id')->nullable()->index();
            $table->unsignedInteger('credits')->default(0);
            $table->enum('status', ['waiting', 'returned', 'completed', 'overbooking'])->default('waiting');

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
        Schema::dropIfExists('waitlist');
    }
}
