<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:09 PM
 */

namespace App\Librerias\Payments;


use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Validation\Validator;

interface PaymentMethodInterface
{
    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public function ConfigurationInputs(Brand $brand = null): string;

    /**
     * @param Purchase  $purchase
     * @param array     $paymentData
     * @param Validator $validator
     *
     * @return void
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator);

    /**
     * @param Brand       $brand
     *
     * @param UserProfile $userProfile
     *
     * @return mixed
     */
    public function getPaymentUserInfoInBrand(Brand $brand, UserProfile $userProfile);

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return mixed
     */
    public function addPaymentOption(Brand $brand, UserProfile $userProfile, $option);

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return mixed
     */
    public function removePaymentOption(Brand $brand, UserProfile $userProfile, $option);
}
