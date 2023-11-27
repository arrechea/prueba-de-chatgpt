<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlobalPurchaseToMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->unsignedInteger('global_purchase')->after('reservations_max');
            $table->unsignedInteger('total_purchase')->nullable()->after('global_purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
           $table ->dropColumn('global_purchase');
           $table ->dropColumn('total_purchase');
        });
    }
}
