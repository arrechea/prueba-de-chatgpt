<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailchimpApikeyAndMailchimpListIdToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('mailchimp_apikey')->nullable()->after('mail_from');
            $table->string('mailchimp_list_id')->nullable()->after('mailchimp_apikey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('mailchimp_apikey');
            $table->dropColumn('mailchimp_list_id');
        });
    }
}
