<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 31/07/2018
 * Time: 12:25 PM
 */

namespace App\Librerias\Helpers;


class LibMath
{
    public static function percentage(float $num = 0, float $div = 0)
    {
        if (!$div)
            return 0;
        else
            return ($num / $div) * 100;
    }
}
