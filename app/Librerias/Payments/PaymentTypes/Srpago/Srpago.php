<?php

namespace App\Librerias\Payments\PaymentTypes\Srpago;


use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use Illuminate\Validation\Validator;

class Srpago extends PaymentMethod
{
    protected $name = 'Srpago';
    protected $payment_slug = 'srpago';

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

    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public function ConfigurationInputs(Brand $brand = null): string
    {
        $settings = null;
        if ($brand) {
            $this->setMethod($brand);

            $settings = $this->getConfig();
        }

        return VistasGafaFit::view('admin.company.Brands.payments.srpago', [
            'name'     => $this->name,
            'slug'     => $this->payment_slug,
            'brand'    => $brand,
            'settings' => $settings,
            'method'   => $this->method,
        ])->render();
    }
}