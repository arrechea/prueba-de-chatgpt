<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 03/07/2018
 * Time: 10:57 AM
 */

namespace App\Librerias\Money;


use App\Models\Brand\Brand;
use App\Models\Currency\Currencies;

class LibMoney
{
    /**
     * Formato de precio.
     *
     * @param $cantidad
     *
     * @return string
     */
    public static function priceFormat($cantidad)
    {
        return number_format($cantidad, 2, '.', ',');
    }

    /**
     * Acomodo de el precio con el prefijo y sufijo del tipo de moneda
     *
     * @param $currency
     * @param $cantidad
     *
     * @return string
     */
    public static function currencyFormat($currency, $cantidad)
    {
        $cantidad = LibMoney::priceFormat($cantidad);
        return  "{$currency->prefijo} {$cantidad} {$currency->sufijo}";
    }

}
