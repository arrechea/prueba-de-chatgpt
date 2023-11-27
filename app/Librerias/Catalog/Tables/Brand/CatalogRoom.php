<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 14/01/2019
 * Time: 09:39
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Helpers\LibFilters;

class CatalogRoom extends \App\Librerias\Catalog\Tables\Location\CatalogRoom
{
    protected static function filtrarQueries(&$query)
    {
        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', (int)$brands_id);
    }
}
