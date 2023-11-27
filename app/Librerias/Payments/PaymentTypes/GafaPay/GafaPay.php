<?php

namespace App\Librerias\Payments\PaymentTypes\GafaPay;
use App\Librerias\Payments\PaymentMethod;
use App\Models\Purchase\Purchase;
use Illuminate\Validation\Validator;

class GafaPay extends PaymentMethod
{
    protected $name = 'GafaPay';
    protected $payment_slug = 'gafapay';

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