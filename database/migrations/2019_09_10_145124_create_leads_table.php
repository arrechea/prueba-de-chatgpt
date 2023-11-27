<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
//            $table->increments('id');
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('password_raw');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('step_name');
            $table->string('company')->nullable();
            $table->boolean('has_paid')->default(false);
//            $table->string('json');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
