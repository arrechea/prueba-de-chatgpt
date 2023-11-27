<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceSpecialTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_special_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('services_id')->index('services_id');
            $table->unsignedInteger('companies_id')->index('companies_id');
            $table->unsignedInteger('brands_id')->index('brands_id');
            $table->string('tag');
            $table->unsignedInteger('order')->default(0)->index('order');
            $table->string('title');
            $table->longText('description')->nullable();

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
        Schema::dropIfExists('service_special_texts');
    }
}
