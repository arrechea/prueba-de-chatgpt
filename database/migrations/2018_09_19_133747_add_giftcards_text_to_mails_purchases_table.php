<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGiftcardsTextToMailsPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mails_purchases', function (Blueprint $table) {
            $table->string('giftcards_text')->nullable()->after('confirmation_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mails_purchases', function (Blueprint $table) {
            $table->dropColumn('giftcards_text');
        });
    }
}
