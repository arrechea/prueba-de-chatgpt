<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLevelInCombosAndMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('combos', function (Blueprint $table) {
//            $table
//                ->enum('level', [
//                    'company',
//                    'brand',
//                ])
//                ->default('brand')
//                ->after('brands_id');
//        });
        Schema::table('memberships', function (Blueprint $table) {
            $table
                ->enum('level', [
                    'company',
                    'brand',
                    'location',
                ])
                ->default('location')
                ->after('brands_id');
        });
        $this->permitirNulosEnMembresiasYCreditos();
    }
    /**
     *
     */
    public function permitirNulosEnMembresiasYCreditos()
    {
//        Schema::table('users_credits', function (Blueprint $table) {
//            $table->unsignedInteger('locations_id')->nullable()->change();
//            $table->unsignedInteger('brands_id')->nullable()->change();
//        });
        Schema::table('users_memberships', function (Blueprint $table) {
            $table->unsignedInteger('locations_id')->nullable()->change();
            $table->unsignedInteger('brands_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('combos', function (Blueprint $table) {
//            $table->dropColumn('level');
//        });
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
}
