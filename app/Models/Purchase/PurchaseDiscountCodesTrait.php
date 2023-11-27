<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/09/2018
 * Time: 01:55 PM
 */

namespace App\Models\Purchase;


use App\Models\Brand\Brand;
use App\Models\DiscountCode\DiscountCode;

trait PurchaseDiscountCodesTrait
{

    /**
     * @return mixed
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }

    /**
     * @return mixed
     */
    public function items()
    {
        return $this->purchase->items();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    /**
     *
     */
    public function apply()
    {
        $this->applied = true;
        $this->save();
    }

    public function discount_code(){
        return $this->belongsTo(DiscountCode::class,'discount_codes_id');
    }
}
