<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->index('users_id');
            $table->string('password');
            $table->integer('companies_id')->unsigned()->index('companies_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->string('email')->nullable();

            $table->string('picture')->nullable();

            $table->string('address')->nullable();
            $table->string('external_number')->nullable();
            $table->string('internal_number')->nullable();

            $table->string('postal_code')->nullable();
            $table->string('municipality')->nullable();

            $table->string('city')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->integer('countries_id')->unsigned()->nullable()->index('countries_id');

            $table->string('icon')->default('');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('cel_phone', 45)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index('status');

            $table->boolean('verified')->default(false);
            $table->string('token', 500)->nullable();

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
        Schema::dropIfExists('user_profiles');
    }
}
