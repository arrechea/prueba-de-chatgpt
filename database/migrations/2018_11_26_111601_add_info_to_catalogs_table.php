<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Librerias\Catalog\LibCatalogsTable;

class AddInfoToCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('catalogs', function (Blueprint $table) {
//
//        });

        \App\Models\Catalogs\Catalogs::updateOrCreate([
            'id' => LibCatalogsTable::UserProfile,
            'name'  => 'catalog.UserProfile',
            'table' => 'user_profiles',
        ]);
        \App\Models\Catalogs\Catalogs::updateOrCreate([
            'id' => LibCatalogsTable::Meetings,
            'name'  => 'catalog.Meetings',
            'table' => 'meetings',
        ]);
        \App\Models\Catalogs\Catalogs::updateOrCreate([
            'id' => LibCatalogsTable::Staff,
            'name'  => 'catalog.Staff',
            'table' => 'staff',
        ]);
        \App\Models\Catalogs\Catalogs::updateOrCreate([
            'id' => LibCatalogsTable::Services,
            'name'  => 'catalog.Services',
            'table' => 'services',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('catalogs', function (Blueprint $table) {
//
//        });
    }
}
