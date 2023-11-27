<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Payments\LibPayments;
use App\Models\Brand\Brand;
use App\Models\Payment\PaymentType;
use Illuminate\Support\Facades\Auth;

class PaymentsApiController extends ApiController
{

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentMethodsInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        $paymentTypes = $brand->validPaymentTypes();
        $info = LibPayments::getPaymentUserInfoInBrand($profile, $brand, $paymentTypes);

        return response()->json($info);
    }

    /**
     * @param Request     $request
     * @param PaymentType $paymentType
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPaymentMethod(Request $request, Brand $brand, PaymentType $paymentType)
    {
        $this->validate($request, [
            'option'       => 'required',
            'option.token' => 'required',
            'option.phone' => 'required',
        ]);
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        $options = $request->get('option', null);

        $token = $options['token']??'';
        $phone = $options['phone']??'';
        if ($phone) {
            $profile->phone = $phone;
            $profile->save();
        }

        $paymentHandler = $paymentType->getPaymentEspecificHandler();

        $paymentHandler->addPaymentOption($brand, $profile, $token);

        //Ahora devolvemos todas las tarjetas
        $paymentTypes = $brand->validPaymentTypes();
        $info = LibPayments::getPaymentUserInfoInBrand($profile, $brand, $paymentTypes);

        return response()->json($info);
    }

    /**
     * @param Request     $request
     * @param Brand       $brand
     * @param PaymentType $paymentType
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePaymentMethod(Request $request, Brand $brand, PaymentType $paymentType)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        $paymentHandler = $paymentType->getPaymentEspecificHandler();

        $paymentHandler->removePaymentOption($brand, $profile, $request->get('option'));

        //Ahora devolvemos todas las tarjetas
        $paymentTypes = $brand->validPaymentTypes();
        $info = LibPayments::getPaymentUserInfoInBrand($profile, $brand, $paymentTypes);

        return response()->json($info);
    }
}
