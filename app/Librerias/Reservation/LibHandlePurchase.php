<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 11/06/18
 * Time: 17:11
 */

namespace App\Librerias\Reservation;


use App\Librerias\Subscriptions\LibSubscriptions;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use App\Models\User\UserWaivers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;

class LibHandlePurchase
{
    /**
     * @param Request  $request
     * @param Location $location
     *
     * @param bool     $isAdmin
     *
     * @return null
     * @throws ValidationException
     */
    static private function prepareSignature(Request $request, Location $location, bool $isAdmin)
    {
        $brand = $location->brand;

        if (
            $brand->needWaiver()
            ||
            $location->needWaiver()
        ) {
            /**
             * @var UserProfile $userProfile
             */
            if ($isAdmin) {
                //Es administrador
                $userProfile = UserProfile::where('id', $request->get('users_id'))
                    ->where('companies_id', $location->companies_id)
                    ->first();
            } else {
                //No es administrador
                $userProfile = Auth::user()->getProfileInThisCompany();
            }

            if ($location->needWaiver()) {
                //Se necesita firma en location
                if ($userProfile->hasWaiverInLocation($location)) {
                    //El usuario tiene waiver en location, por ende tambien tiene en brand
                    return null;
                }
            } else if ($brand->needWaiver() && $userProfile->hasWaiverInBrand($brand)) {
                //No necesita en location pero se necesita en brand y tiene
                return null;
            }

            $validator = Validator::make($request->all(), [
                'signature' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $userWaiver = new UserWaivers();
            $userWaiver->users_id = $userProfile->users_id;
            $userWaiver->users_profile_id = $userProfile->id;
            $userWaiver->companies_id = $location->companies_id;
            $userWaiver->brands_id = $location->brands_id;
            $userWaiver->locations_id = $location->id;
            $userWaiver->signature = $request->get('signature', null);
            $userWaiver->save();
        }
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    static public function purchase(Request $request, Company $company, Location $location, bool $isAdmin)
    {
        $location->load('brand');

        self::prepareSignature($request, $location, $isAdmin);

        $subscribe = $request->input('subscribe') === 'on' || $request->input('subscribe') === 'true';
        $set_payment = $request->input('set_payment') === 'on' || $request->input('set_payment') === 'true';
        $test = $request->get('test', false);
        $reservations = null;

        //BuySystem
        $purchase = LibGeneratePurchase::create($request, $company, $location, $isAdmin);

        //PaymentSystem
        if ($purchase && $purchase->needPayment()) {
            LibPaymentGenerator::generate($request, $purchase, $company, $location, $isAdmin);
        }

        if (($subscribe && $set_payment) && $purchase) {
//            LibSubscriptions::create($request, $isAdmin, $company, $location->brand, $location, $purchase, $set_payment);
        }
        //ProcessBuySystem
        if ($purchase) {
            //Solo si hay compra se hace asignacion
            //Solo si la compra no es un giftCard
            $purchase->assignToUser($subscribe);
        }

        //ReservationSystem
        if ($request->get('meetings_id', null)) {
            $reservations = LibReservation::create($request, $company, $location, $isAdmin);
        }

        if ($reservations) {
            //populate
            if (method_exists($reservations, 'load')) {
                $reservations->load([
                    'staff',
                    'meetings.service',
                    'object',
                ]);
            }
        }
        if ($purchase) {
            //populate
            if (method_exists($purchase, 'load')) {
                $purchase->load([
                    'items.buyed',
                    'giftCard',
                ]);
            }
        }

        return response()->json([
            'reservation' => $reservations,
            'purchase'    => $purchase,
        ]);
    }
}
