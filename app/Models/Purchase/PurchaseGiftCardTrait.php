<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/09/18
 * Time: 09:06
 */

namespace App\Models\Purchase;


use App\Events\Purchases\GiftCardRedeemed;
use App\Librerias\GiftCards\LibGiftCards;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\User\UserProfile;
use Carbon\Carbon;

trait PurchaseGiftCardTrait
{
    /**
     * @param array $values
     *
     * @return mixed
     */
    public function getArrayableItems(array $values)
    {
        //Dates
        if (!in_array('redempted_at', $this->dates)) {
            $this->dates[] = 'redempted_at';
        }

        if (!in_array('is_redempted', $this->casts)) {
            $this->casts[] = [
                'is_redempted' => 'boolean',
            ];
        }

        return parent::getArrayableItems($values);
    }

    /**
     *
     */
    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }

    public function items()
    {
        return $this->purchase->items();
    }

    /**
     * @param UserProfile $profile
     * @param             $redeemed_by
     *
     * @return $this
     */
    public function assignToUser(UserProfile $profile, AdminProfile $redeemed_by = null)
    {
        $purchase = $this->purchase;
        if ($this->isRedeemed()) {
            return __('giftcards.ErrorAlreadyRedeemed');
        }

        if (!$this->isRedeemed() && $purchase->isComplete() && $purchase->isGiftCard()) {
            $items = $this->items;
            $brand = $this->brand;
            if ($items->count() > 0) {
                $items->each(function ($item) use ($profile) {
                    /**
                     * @var PurchaseItems $item
                     */
                    $item->assignToUser($profile);
                });
            }

            if ($redeemed_by) {
                $this->redempted_by_admin_profiles_id = $redeemed_by->id;
            }

            $this->is_redempted = true;
            $this->redempted_at = $brand->now();
            $this->redempted_by_user_profiles_id = $profile->id;

            $this->save();

            event(new GiftCardRedeemed($this, $profile));

            return $this;
        }

        return __('giftcards.ErrorInvalidCode');
    }

    public function isRedeemed()
    {
        return $this->is_redempted;
    }

    public function checkCode()
    {
        return LibGiftCards::checkCode($this->code, $this->brands_id);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class, 'redempted_by_user_profiles_id');
    }

    public function admin_profile()
    {
        return $this->belongsTo(AdminProfile::class, 'redempted_by_admin_profiles_id');
    }
}
