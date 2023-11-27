<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:32 PM
 */

namespace App\Librerias\Payments\PaymentTypes\Conekta;

use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Countries;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Validator as ValidatorMaker;
use Illuminate\Validation\ValidationException;

class Conekta extends PaymentMethod
{
    protected $payment_slug = 'conekta';
    protected $method;

    /**
     * @param Purchase  $purchase
     * @param array     $paymentData
     * @param Validator $validator
     *
     * @return void
     * @throws ValidationException
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator)
    {
        //No Need
        $userProfile = $purchase->user_profile;
        $validator->after(function ($validator) use (
            $userProfile
        ) {
            if (!$userProfile) {
                $validator->errors()->add('payment_id', __('reservation-fancy.error.conekta.NotConfigured'));
            }
        });
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $this->updateUser($userProfile, $paymentData);
        //necesitamos comprobar en gafapay
        $this->checkPaymentInGafaPay($purchase);
    }

    /**
     * @param UserProfile $userProfile
     * @param array       $paymentData
     */
    private function updateUser(UserProfile $userProfile, array $paymentData)
    {
        if (count($paymentData)) {
            //Actualizar Usuario
            $userProfile->phone = $paymentData['phone'] ?? $userProfile->phone;
            $userProfile->address = $paymentData['address'] ?? $userProfile->address;
            $userProfile->external_number = $paymentData['external_number'] ?? $userProfile->external_number;
            $userProfile->internal_number = $paymentData['internal_number'] ?? $userProfile->internal_number;
            $userProfile->postal_code = $paymentData['postal_code'] ?? $userProfile->postal_code;
            if (isset($paymentData['countries_id'])) {
                $pais = Countries::where('code2', $paymentData['countries_id'])->first();
                if ($pais) {
                    $userProfile->countries_id = $pais->id ?? $userProfile->countries_id;
                }
            }
            $userProfile->save();
        }
    }

    /**
     * @param Brand|null $brand
     *
     * @return string
     * @throws \Throwable
     */
    public function ConfigurationInputs(Brand $brand = null): string
    {
        $settings = null;
        if ($brand) {
            $this->setMethod($brand);

            $settings = $this->getConfig();
            $settings['only_virtual_products'] = ($settings['only_virtual_products'] == true) ? 'on' : '';
        }

        return VistasGafaFit::view('admin.company.Brands.payments.conekta', [
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
            'conekta'
        );

        return new Collection(array_values((array)$cards));
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return Collection
     * @throws ValidationException
     */
    public function addPaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {
        $validator = ValidatorMaker::make([
            'cardToken' => $option,
        ], [
            'cardToken' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $cards = LibGafaPay::addPaymentSourcesByEmail(
            $brand->gafapay_client_id,
            $brand->gafapay_client_secret,
            [
                'token' => $option,
                'phone' => $userProfile->phone,
            ],
            $userProfile->email,
            'conekta'
        );

        return new Collection(array_values((array)$cards));
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return Collection
     * @throws ValidationException
     */
    public function removePaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {
        $validator = ValidatorMaker::make([
            'cardToken' => $option,
        ], [
            'cardToken' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $cards = LibGafaPay::removePaymentSourcesByEmail(
            $brand->gafapay_client_id,
            $brand->gafapay_client_secret,
            $option,
            $userProfile->email,
            'conekta'
        );

        return new Collection(array_values((array)$cards));

    }
}
