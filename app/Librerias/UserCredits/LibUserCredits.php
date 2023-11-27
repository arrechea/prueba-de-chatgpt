<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/02/2019
 * Time: 17:47
 */

namespace App\Librerias\UserCredits;


use App\Librerias\GafaPay\LibGafaPay;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\User\UserProfile;
use App\Models\Location\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AdminRequest;
use App\Models\User\UsersMemberships;

class LibUserCredits
{
    /**
     * @param AdminRequest $request
     * @param UserProfile  $profile
     * @param Location     $location
     * @param Company      $company
     * @param Brand        $brand
     */
    public static function modifyCredits(AdminRequest $request, UserProfile $profile, Location $location, Company $company, Brand $brand)
    {
        $credit = (int)$request->input('credits_id');
        $purchase = (int)$request->input('purchases_id');
        $total = (int)$request->input('credits_total');
        $expiration_date = $request->input('expiration_date');
        $wheres = [
            ['purchases_id', $purchase],
            ['credits_id', $credit],
            ['brands_id', $brand->id],
            ['companies_id', $company->id],
            ['locations_id', $location->id],
        ];

        $count = $profile->purchaseCreditCount($purchase, $credit)->first()->total;

        if ($total >= 1) {
            if ($total > $count) {
                $difference = $total - $count;
                $user_credit = $profile->allCredits()->where($wheres)->first()->toArray();
                unset($user_credit['id']);
                unset($user_credit['brand']);
                unset($user_credit['deleted_at']);
                $credits = [];
                for ($i = 1; $i <= $difference; $i++) {
                    $credits[] = $user_credit;
                }
                DB::table('users_credits')->insert($credits);
            } else if ($total < $count) {
                $difference = $count - $total;
                $profile->allCredits()->where($wheres)->take($difference)->delete();
            }
        }

        if ($expiration_date) {
            $profile->allCredits()->where($wheres)->update([
                'expiration_date' => $expiration_date . ' 23:59:59',
            ]);
        }
    }

    /**
     * @param AdminRequest $request
     * @param UserProfile  $profile
     * @param Location     $location
     * @param Company      $company
     * @param Brand        $brand
     */
    public static function modifyActiveMembership(AdminRequest $request, UserProfile $profile, Location $location, Company $company, Brand $brand)
    {
        $id = (int)$request->input('memberships_id');
        $expiration_date = $request->input('expiration_date');

        $membership = UsersMemberships::find($id);
        $purchase = $membership->purchase;
        $subscription = $purchase->subscription??false;
        $paymentIdInGafaPay = $purchase->payment_data_id;

        if ($subscription && $paymentIdInGafaPay) {
            //Send data to gafapay
            $response = LibGafaPay::updateMembership(
                $subscription,
                [
                    'token'             => $paymentIdInGafaPay,
                    'subscription'      => $subscription,
                    'next_payment_date' => $expiration_date,
                ]
            );
            if ($response) {
                $dateConfirmed = $response->data->fechaProximoPago;
                $membership->update([
                    'expiration_date' => $dateConfirmed . ' 23:59:59',
                ]);
            }
        } else {
            $membership->update([
                'expiration_date' => (new Carbon($expiration_date))->endOfDay(),
            ]);
        }
    }

    public static function cancelCredits(AdminRequest $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        $credit = (int)$request->input('credits_id');
        $purchase = (int)$request->input('purchases_id');
        $profile->allCredits()->where([
            ['credits_id', $credit],
            ['purchases_id', $purchase],
            ['brands_id', $brand->id],
            ['companies_id', $company->id],
            ['locations_id', $location->id],
        ])->delete();
    }
}
