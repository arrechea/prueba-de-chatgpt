<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:12 PM
 */

namespace App\Librerias\Payments\PaymentTypes\Cash;

use App\Librerias\Payments\PaymentMethod;
use App\Models\Purchase\Purchase;
use Illuminate\Validation\Validator;

class Cash extends PaymentMethod
{
    protected $name = 'Cash';
    protected $payment_slug = 'cash';

    /**
     * @param Purchase  $purchase
     * @param array     $paymentData
     * @param Validator $validator
     *
     * @return void
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator)
    {
        //No Need
    }
}
