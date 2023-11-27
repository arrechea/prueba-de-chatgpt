<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 07/08/2018
 * Time: 11:37 AM
 */

namespace App\Models\Credit;


use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;

trait CreditsUserRelations
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credits_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id')->withTrashed();
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used === true;
    }

    /**
     * @return bool
     */
    public function isNotUsed(): bool
    {
        return !$this->isUsed();
    }

    /**
     *
     */
    public function markAsUsed()
    {
        if ($this->isNotUsed()) {
            $this->used = true;
            $this->save();
        }
    }

    /**
     * @return mixed
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id')->withTrashed();
    }

    /**
     * @return null
     */
    public function subscription_payment()
    {
        $purchase = $this->purchase;
        if ($purchase) {
            return $purchase->active_subscription_payment();
        } else return null;
    }
}
