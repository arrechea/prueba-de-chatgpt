<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchasesDiscountCodesIdToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table){
           $table->boolean('has_discount_code')->after('users_id')->default(false);
           $table->unsignedInteger('discount')->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases',function (Blueprint $table){
           $table->dropColumn('has_discount_code');
           $table->dropColumn('discount');
        });
    }
}
