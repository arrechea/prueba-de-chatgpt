<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGafaFitToCreditsBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_brand', function (Blueprint $table) {
            $table->boolean('gafa_fit')->default(false)->after('credits_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_brand', function (Blueprint $table) {
            $table->dropColumn('gafa_fit');
        });
    }
}
