<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 04/03/2019
 * Time: 10:43
 */

namespace App\Librerias\Subscriptions;


use App\Events\Subscriptions\PaymentCreated;
use App\Librerias\Payments\PaymentTypes\Conekta\Conekta;
use App\Librerias\Payments\PaymentTypes\Srpago\Srpago;
use App\Librerias\Payments\PaymentTypes\Stripe\Stripe;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Membership\Membership;
use App\Models\Payment\PaymentType;
use App\Models\Purchase\Purchase;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\UserRecurrentPayment;
use App\Models\User\UserProfile;
use App\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibSubscriptions
{
    const SUBSCRIPTION_PAYMENT_TYPES = [
        'conekta' => Conekta::class,
        'stripe'  => Stripe::class,
        'srpago'  => Srpago::class,
    ];

    static private function getUserAdmin(bool $isAdmin, &$admin, &$user, Request $request, Company $company)
    {
        if ($isAdmin) {
            //Es administrador
            $admin = Auth::user()->getProfileInThisCompany();
            $user = UserProfile::where('id', $request->get('users_id'))
                ->where('companies_id', $company->id)
                ->first();
        } else {
            //No es administrador
            $user = Auth::user()->getProfileInThisCompany();
        }
    }

    /**
     * @param Request  $request
     * @param bool     $isAdmin
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Purchase $purchase
     * @param bool     $set_payment
     */
    static public function create(Request $request, bool $isAdmin, Company $company, Brand $brand, Location $location, Purchase $purchase, bool $set_payment = false)
    {
        $class = null;
        $product = null;
        if ($purchase) {
            /**
             * @var UserProfile $user
             */
            self::getUserAdmin($isAdmin, $admin, $user, $request, $company);

            $recurrent_payment = LibSubscriptions::assignRecurrentPayment($user, $request, $company, $location->brand, $location, $set_payment);

            $payment_types_id = $request->input('payment_types_id');

            $subscriptionIdGafapay = $request->input('subscriptionId');

            $payment_type = PaymentType::find($payment_types_id);

            if (!array_search($payment_type->model, LibSubscriptions::SUBSCRIPTION_PAYMENT_TYPES)) {
                throw new ValidationException('subscription', __('reservation-fancy.error.subscription'));
            }

            if ($request->has('memberships_id') && $request->input('memberships_id')) {
                $memberships_array = $request->memberships_id;
                $class = Membership::class;
                $product = Membership::find($memberships_array[0]);
            } else if ($request->has('combos_id') && $request->input('combos_id')) {
                $combos_array = $request->combos_id;
                $class = Combos::class;
                $product = Combos::find($combos_array[0]);
            }

            if ($product && $class && $recurrent_payment) {
                if ($user->hasAnSubscription($location, $product)) {
                    return;
                }

                $subscription = new Subscription();
                $subscription->users_profiles_id = $user->id;
                $subscription->users_id = $user->users_id;
                $subscription->companies_id = $company->id;
                $subscription->locations_id = $location->id;
                $subscription->brands_id = $brand->id;
                $subscription->admin_profiles_id = $admin ? $admin->id : null;
                $subscription->users_recurrent_payments_id = $recurrent_payment->id;
                $subscription->product_type = $class;
                $subscription->product_id = $product[0]->id;
                $subscription->status = 'active';
                $subscription->recurrence_days = $product[0]->expiration_days ?? 30;
                $subscription->subscription_id_gafapay = $subscriptionIdGafapay;

                $subscription->save();

                $payment = $subscription->addPayment($purchase);

                try {
                    event(new PaymentCreated($payment, $user));
                } catch (\Exception $e) {

                }
            }
        }
    }

    /**
     * @param UserProfile $user
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param bool        $set_payment
     *
     * @return UserRecurrentPayment
     */
    static public function assignRecurrentPayment(UserProfile $user, Request $request, Company $company, Brand $brand, Location $location, bool $set_payment)
    {
        $recurrent_payment = $user->recurrent_payment()->where([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
            'locations_id' => $location->id,
        ])->first();

        if (!$recurrent_payment && $set_payment) {
            $recurrent_payment = new UserRecurrentPayment();
        }

        if ($set_payment) {
            $payment_types_id = $request->input('payment_types_id');
            $payment_data = $request->input('payment_data');

            $recurrent_payment->payment_types_id = $payment_types_id;
            $recurrent_payment->users_id = $user->users_id;
            $recurrent_payment->users_profiles_id = $user->id;
            $recurrent_payment->companies_id = $company->id;
            $recurrent_payment->brands_id = $brand->id;
            $recurrent_payment->locations_id = $location->id;
            $recurrent_payment->payment_data = json_encode($payment_data);
            $recurrent_payment->save();
        }

        return $recurrent_payment;
    }
}
