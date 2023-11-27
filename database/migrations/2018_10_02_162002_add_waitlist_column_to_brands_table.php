<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaitlistColumnToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('waitlist')->after('mail_from')->default(false);
            $table->longText('explanation_waitlist')->after('waitlist')->nullable();
            $table->unsignedInteger('max_waitlist')->after('explanation_waitlist')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('waitlist');
            $table->dropColumn('explanation_waitlist');
            $table->dropColumn('max_waitlist');
        });
    }
}
