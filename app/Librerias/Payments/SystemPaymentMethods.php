<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:35 PM
 */

namespace App\Librerias\Payments;


use App\Models\Payment\PaymentType;

abstract class SystemPaymentMethods
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function get()
    {
        return PaymentType::all();
    }
}
