<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('hide_in_home')->default(true);
            $table->string('pic')->nullable();
            $table->string('category')->nullable();
            $table->integer('companies_id')->unsigned()->nullable()->index('companies_id');
            $table->integer('brands_id')->unsigned()->nullable()->index('brands_id');
            $table->integer('parent_id')->unsigned()->nullable()->index('parent_id');
            $table->enum('status', ['active', 'inactive'])->default('active');

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
        Schema::dropIfExists('services');
    }
}
