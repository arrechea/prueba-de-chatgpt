<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('companies_id')->nullable()->index('companies_id');
            $table->string('color_black')->nullable();
            $table->string('color_main')->nullable();
            $table->string('color_secondary')->nullable();
            $table->string('color_secondary2')->nullable();
            $table->string('color_secondary3')->nullable();
            $table->string('color_light')->nullable();
            $table->string('color_menutop')->nullable();
            $table->string('color_menuleft')->nullable();
            $table->string('color_menuleft_secondary')->nullable();
            $table->string('color_menuleft_selected')->nullable();
            $table->string('color_alert')->nullable();

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
        Schema::dropIfExists('companies_colors');
    }
}
