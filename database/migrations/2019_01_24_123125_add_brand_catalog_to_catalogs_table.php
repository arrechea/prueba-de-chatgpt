<?php

use App\Librerias\Catalog\LibCatalogsTable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandCatalogToCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Catalogs\Catalogs::updateOrCreate([
            'id' => LibCatalogsTable::Brand,
            'name'  => 'catalog.Brand',
            'table' => 'brands',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
