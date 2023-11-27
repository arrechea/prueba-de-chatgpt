<?php

namespace App\Librerias\Payments\PaymentTypes\Terminal;


use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use Illuminate\Validation\Validator;

class Terminal extends PaymentMethod
{
    protected $name = 'Terminal';
    protected $payment_slug = 'terminal';

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