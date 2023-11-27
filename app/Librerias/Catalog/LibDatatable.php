<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 15/03/18
 * Time: 13:21
 */

namespace App\Librerias\Catalog;


abstract class LibDatatable
{
    /**
     * @return mixed
     */
    static public function GetTableId()
    {
        return str_replace('.', '', microtime(true));
    }
}
