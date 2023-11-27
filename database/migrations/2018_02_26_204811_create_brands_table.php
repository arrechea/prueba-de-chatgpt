<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable()->index('email');

            $table->string('copyright')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('google_plus')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('spotify')->nullable();
            $table->string('tumblr')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('pinterest')->nullable();


            $table->string('job')->nullable();
            $table->longText('description')->nullable();
            $table->integer('cancelation_dead_line')->nullable()->default(0);

            $table->boolean('waiver_forze')->default(false);
            $table->longText('waiver_text')->nullable();

            $table->integer('currencies_id')->unsigned()->index('currencies_id');
            $table->integer('language_id')->unsigned()->default(2)->index('language_id');
            $table->string('street')->nullable();
            $table->string('external_number')->nullable();
            $table->string('suburb')->nullable();
            $table->string('postcode')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('country_states_id')->nullable()->index('country_states_id');
            $table->integer('countries_id')->unsigned()->nullable()->index('countries_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('pic')->nullable();
            $table->string('banner')->nullable();

            $table->integer('companies_id')->unsigned()->index('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies');


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
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['companies_id']);
        });

        Schema::dropIfExists('brands');
    }
}
