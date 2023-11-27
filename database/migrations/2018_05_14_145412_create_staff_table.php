<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('slug')->unique();


            $table->unsignedInteger('companies_id')->nullable()->index('companies_id');
            $table->unsignedInteger('admin_profile_id')->nullable();

            $table->string('job')->nullable();
            $table->string('quote')->nullable();
            $table->longText('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();

            $table->string('picture_web')->nullable();
            $table->string('picture_web_list')->nullable();
            $table->string('picture_web_over')->nullable();
            $table->string('picture_movil')->nullable();
            $table->string('picture_movil_list')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->boolean('hide_in_home')->default(true);
            $table->string('address')->nullable();
            $table->integer('external_number')->nullable();
            $table->string('municipality')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->unsignedInteger('countries_id')->unsigned()->nullable()->index('countries_id');

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
        Schema::dropIfExists('staff');
    }
}
