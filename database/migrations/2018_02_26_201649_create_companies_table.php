<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable()->index();
            $table->integer('admins_id')->unsigned()->nullable()->index('admins_id');
            $table->foreign('admins_id')->references('id')->on('admins');

            $table->string('address')->nullable();
            $table->string('external_number')->nullable();
            $table->string('municipality')->nullable();
            $table->string('postal_code')->nullable();

            $table->string('city')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->unsignedInteger('countries_id')->unsigned()->nullable()->index('countries_id');

            $table->string('copyright')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->unsignedInteger('language_id')->index('language_id')->default(2);
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
        Schema::dropIfExists('companies');
    }
}
