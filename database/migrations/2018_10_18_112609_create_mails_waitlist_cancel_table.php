<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailsWaitlistCancelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails_waitlist_cancel', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brands_id')->index();
            $table->string('logo')->nullable();
            $table->string('text')->nullable();
            $table->string('background_img')->nullable();
            $table->string('waitlist_link')->nullable();
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
        Schema::dropIfExists('mails_waitlist_cancel');
    }
}
