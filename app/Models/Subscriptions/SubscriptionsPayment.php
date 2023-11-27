<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 04/03/2019
 * Time: 11:09
 */

namespace App\Models\Subscriptions;


use App\Librerias\Money\LibMoney;
use App\Models\GafaFitModel;
use App\Models\Purchase\Purchase;

class SubscriptionsPayment extends GafaFitModel
{
    protected $table = 'subscriptions_payments';

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscriptions_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }

    public function items()
    {
        return $this->purchase->items();
    }
}
