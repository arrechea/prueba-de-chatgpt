<?php

namespace App\Http\Controllers;

use App\Librerias\Payments\SystemPaymentMethods;
use App\Librerias\Reservation\ReservationControllerTrait;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\Payment\PaymentType;
use Illuminate\Http\Request;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Reservation\LibHandlePurchase;
use App\Librerias\Reservation\LibReservationForm;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use App\User;
use Illuminate\Support\Facades\Auth;

class AppController extends ApiController implements ReservationControllerTrait
{

    /**
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    function autologin(Brand $brand, Location $location, UserProfile $user)
    {
        $request = request();
        Auth::login($user->user, true);

        $variables = [
            'brand'         => $brand,
            'locationToSee' => $location,
            'user'          => $user,
        ];
        $variables = array_merge($variables, $request->all());

        return redirect()->route('cosmics.getForm', $variables)->withInput();
    }

    /**
     * Solo tiene soporte a conekta
     *
     * @param Request          $request
     * @param Brand            $brand
     * @param Location         $location
     * @param UserProfile|User $user
     * @param PaymentType      $paymentType
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPaymentToken(Request $request, Brand $brand, Location $location, UserProfile $user, PaymentType $paymentType)
    {
        $card = $request->get('card', '');

        $payment_methods = SystemPaymentMethods::get()
            ->filter(function ($item) {
                return !!class_exists($item->model);
            })
            ->map(function ($item) use ($brand) {
                $paymentSingle = new $item->model(
                    $item,
                    $brand->gafapay_brand_id,
                    $brand->gafapay_client_id,
                    $brand->gafapay_client_secret
                );


                return [
                    'config' => $paymentSingle->getConfig(),
                    'slug'   => $item->slug,
                ];
            });

        return VistasGafaFit::view('reservation.conekta-token', [
            'payment_types' => $payment_methods,//$brand->validPaymentTypes(false),
            'card'          => $card,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getForm(Request $request)
    {
        $location = LibRoute::getLocation(\request());

        return LibReservationForm::generateForm($request, $location->company, $location, false, true);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function reservate(Request $request)
    {
        $location = LibRoute::getLocation(\request());

        return LibHandlePurchase::purchase($request, $location->company, $location, false);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateGiftCode()
    {
        $location = LibRoute::getLocation(\request());

        return LibReservationForm::generateGiftCode($this->getCompany(), $location->brand);
    }

    /**
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $user
     * @param string      $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkGiftCode(Brand $brand, Location $location, UserProfile $user, string $code)
    {
        $isValid = LibReservationForm::isGiftCodeValidToGenerate($code, $brand);
        if ($isValid) {
            return response()->json(true, 200);
        } else {
            return response()->json(true, 409);
        }
    }

    /**
     * @param Request     $request
     * @param Brand       $brand
     * @param             $location
     * @param User        $user
     * @param string      $code
     * @param UserProfile $userProfile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDiscountCode(Request $request, Brand $brand, $location, User $user, string $code, UserProfile $userProfile)
    {
        return LibReservationForm::responseDiscountCodeValid($code, $brand, $userProfile, $request);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getFormTemplate(Request $request)
    {
        return $this->getForm($request);
    }

    /**
     * @param Request $request
     * @param int     $companyOfUser
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function recoveryPassword(Request $request, int $companyOfUser)
    {
        $company = Company::find($companyOfUser);
        if (!$company) {
            return abort(404);
        }

        return VistasGafaFit::view('auth.widget.recovery-password', [
            'companyOfUser' => $companyOfUser,
            'email' => $request->get('email'),
            'token' => $request->get('token'),
        ]);
    }
}
