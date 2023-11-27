<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/02/2018
 * Time: 04:01 PM
 */

namespace App\Librerias\Vistas;


abstract class VistasGafaFit
{
    /**
     * Sistema central de vistas
     *
     * @param null  $view
     * @param array $data
     * @param array $mergeData
     *
     * @param bool  $forzeJSON
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    static public function view($view = null, $data = [], $mergeData = [], $forzeJSON = false)
    {
        return $forzeJSON ? response()->json($data) : view($view, $data, $mergeData);
    }
}
