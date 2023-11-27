<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 06/03/2019
 * Time: 16:15
 */

namespace App\Models\Subscriptions;


use App\Events\Subscriptions\PaymentCreated;
use App\Events\Subscriptions\PaymentError;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Validator;

trait SubscriptionTrait
{
    public function addPayment(Purchase $purchase = null, string $error = null)
    {
        $now = Carbon::now();
        $expiration = Carbon::now()->setTime(23, 59, 59, 999);

        $payment = new SubscriptionsPayment();
        $payment->subscriptions_id = $this->id;
        $payment->users_profiles_id = $this->users_profiles_id;
        $payment->users_id = $this->users_id;
        $payment->purchases_id = $purchase ? $purchase->id : null;
        $payment->companies_id = $this->companies_id;
        $payment->brands_id = $this->brands_id;
        $payment->locations_id = $this->locations_id;
        $payment->status = $purchase && !$error ? 'complete' : 'incomplete';
        $payment->completion_time = $purchase && !$error ? $now : null;
        $payment->renewal_time = $purchase && !$error ? $expiration->addDays($this->product->expiration_days - 1 ?? 29) : null;
        $payment->save();

        return $payment;
    }

    public function paymentToResolve()
    {
        return $this->payments()->where([
            ['status', 'complete'],
            ['renewal_time', '<', Carbon::now()],
            ['renewed', false],
        ]);
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionsPayment::class, 'subscriptions_id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->morphTo('product')->withTrashed();
    }

    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class, 'users_profiles_id');
    }

    public function cancel()
    {
        if ($this->status === 'active') {
            $this->status = 'inactive';
            $this->save();
        }
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id');
    }

    public function recurrent_payment()
    {
        return $this->belongsTo(UserRecurrentPayment::class, 'users_recurrent_payments_id');
    }

    public function renew()
    {
        if ($this->status === 'active' && $this->paymentToResolve()->count() > 0) {
            $product = $this->product;
            $complete = false;
            $tries = $product->number_of_tries ?? 3;
            $i = 1;
            $profile = $this->user_profile;

            do {
                $subscription_payment = new SubscriptionsPayment();
                $subscription_payment->subscriptions_id = $this->id;
                $subscription_payment->users_profiles_id = $this->users_profiles_id;
                $subscription_payment->users_id = $this->users_id;
                $subscription_payment->companies_id = $this->companies_id;
                $subscription_payment->brands_id = $this->brands_id;
                $subscription_payment->locations_id = $this->locations_id;
                $subscription_payment->status = 'incomplete';

                $error = null;

                try {
                    $validator = Validator::make([], []);
                    $company = $this->company;
                    $brand = $this->brand;
                    $location = $this->location;
                    $recurrent_payment = $this->recurrent_payment;
                    if (!$recurrent_payment) {
                        throw new \Exception(__('subscriptions.MessageErrorNoRecurrentPayment'));
                    }

                    $payment_type = $recurrent_payment->payment_type;

                    $purchase = new Purchase();
                    $purchase->payment_types_id = $payment_type ? $payment_type->id : null;
                    $purchase->currencies_id = $brand->currencies_id;

                    $purchase->status = $purchase::$statusPending;
                    $purchase->is_gift_card = false;
                    $purchase->has_discount_code = false;

                    $purchase->locations_id = $location->id;
                    $purchase->brands_id = $location->brands_id;
                    $purchase->companies_id = $location->companies_id;

                    $purchase->user_profiles_id = $this->users_profiles_id;
                    $purchase->users_id = $this->users_id;

                    $purchase->admin_profiles_id = null;
                    $purchase->subtotal = 0;
                    $purchase->total = 0;
                    $purchase->save();
                    $item = $purchase->addItem($product);

                    //Actualizado de precios
                    $purchase->subtotal = $item->item_price_final;
                    $purchase->total = $item->item_price_final;
                    $purchase->save();

                    $paymentData = json_decode($recurrent_payment->payment_data, true);

                    if (isset($paymentData['saveCard']) && $paymentData['saveCard'] === 'true') {
                        $paymentData['saveCard'] = 'false';
                    }

                    if ($payment_type) {
                        $paymentSystem = $payment_type->getPaymentEspecificHandler();
                        $paymentSystem->GenerateOrder($purchase, $paymentData, $validator);
                    } else {
                        throw new \Exception(__('subscriptions.MessageErrorNoPaymentType'));
                    }
                    $purchase->assignToUser(true);


                    $now = Carbon::now();
                    $expiration = Carbon::now()->setTime(23, 59, 59, 999)->addDays($product->expiration_days - 1 ?? 29);

                    $this->paymentToResolve()->update([
                        'renewed' => true,
                    ]);

                    $subscription_payment->completion_time = $now;
                    $subscription_payment->renewal_time = $expiration;
                    $subscription_payment->purchases_id = $purchase->id;
                    $subscription_payment->status = 'complete';
                    $subscription_payment->save();

                    try {
                        event(new PaymentCreated($subscription_payment, $profile));
                    } catch (\Exception $e) {
                    }

                    $complete = true;
                } catch (ValidationException $e) {
                    $error = json_encode($e->errors());

                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }

                if ($error) {
                    $subscription_payment->completion_time = null;
                    $subscription_payment->renewal_time = null;
                    $subscription_payment->purchases_id = null;
                    $subscription_payment->status = 'error';
                    $subscription_payment->error_message = $error;
                    $subscription_payment->save();
                }

                $i++;
            } while ($i <= ($tries) && !$complete);

            if ($i >= $tries && !$complete) {
                $this->cancel();
                event(new PaymentError($subscription_payment, $profile));
            }

        }
    }
}
