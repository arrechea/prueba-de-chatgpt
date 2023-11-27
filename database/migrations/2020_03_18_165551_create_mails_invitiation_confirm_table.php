<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsInvitiationConfirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails_invitation_confirm', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('brands_id')->index();
            $table->string('text')->nullable();
            $table->string('logo')->nullable();
            $table->string('background_img')->nullable();
            $table->string('reservation_link')->nullable();
            $table->string('logo_link')->nullable();

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
        Schema::dropIfExists('mails_invitation_confirm');
    }
}
