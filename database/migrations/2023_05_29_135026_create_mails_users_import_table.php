<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailsUsersImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails_users_import', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('companies_id')->index();
            $table->string('logo')->nullable();
            $table->string('background_img')->nullable();
            $table->string('logo_link')->nullable();
            $table->json('content')->nullable();
//            $table->string('text_1')->nullable();
//            $table->longText('text_2')->nullable();
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
        Schema::dropIfExists('mails_users_import');
    }
}
