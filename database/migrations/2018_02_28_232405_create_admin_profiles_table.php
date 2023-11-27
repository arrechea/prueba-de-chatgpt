<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admins_id')->unsigned()->index('admins_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->dateTime('birth_date')->nullable();

            $table->string('address')->nullable();
            $table->string('external_number')->nullable();

            $table->string('municipality')->nullable();
            $table->string('postal_code')->nullable();

            $table->string('city')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->unsignedInteger('countries_id')->unsigned()->nullable()->index('countries_id');


            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('cel_phone', 45)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index('status');
            $table->integer('companies_id')->unsigned()->nullable()->default(null)->index('companies_id');
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('pic')->nullable();
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
        Schema::dropIfExists('admin_profiles');
    }
}
