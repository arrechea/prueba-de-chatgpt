<?php

namespace App\Librerias\Payments\PaymentTypes\Stripe;

use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

class Stripe extends PaymentMethod
{
    protected $name = 'Stripe';
    protected $payment_slug = 'stripe';

    /**
     * @param Purchase  $purchase
     * @param array     $paymentData
     * @param Validator $validator
     *
     * @return void
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator)
    {
        //necesitamos comprobar en gafapay
        $this->checkPaymentInGafaPay($purchase);
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
            $settings['only_virtual_products'] = ($settings['only_virtual_products'] == true) ? 'on' : '';
        }

        return VistasGafaFit::view('admin.company.Brands.payments.stripe', [
            'name'     => $this->name,
            'slug'     => $this->payment_slug,
            'brand'    => $brand,
            'settings' => $settings,
            'method'   => $this->method,
        ])->render();
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     *
     * @return Collection
     */
    public function getPaymentUserInfoInBrand(Brand $brand, UserProfile $userProfile)
    {
        $cards = LibGafaPay::getPaymentSourcesByEmail(
            $brand->gafapay_client_id,
            $brand->gafapay_client_secret,
            $userProfile->email,
            'stripe'
        );

        return new Collection(array_values((array)$cards));
    }
}
