<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailsPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brands_id')->index();
            $table->string('logo')->nullable();
            $table->string('background_img')->nullable();
            $table->string('logo_link')->nullable();
            $table->string('store_link')->nullable();
            $table->string('confirmation_text')->nullable();
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
        Schema::dropIfExists('mails_purchases');
    }
}
