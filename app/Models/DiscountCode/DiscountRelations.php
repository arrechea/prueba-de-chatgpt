<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/09/2018
 * Time: 05:51 PM
 */

namespace App\Models\DiscountCode;


use App\Models\Brand\Brand;
use App\Models\Credit\Credit;
use App\Models\Purchase\Purchasable;
use App\Models\Purchase\PurchasesDiscountCodes;
use App\Models\User\UserCategory;
use App\Models\User\UserProfile;
use Carbon\Carbon;

trait DiscountRelations
{

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return bool
     */
    public function isPrice()
    {
        return $this->discount_type === "price";
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->is_public === 1 || $this->is_public === true;
    }

    /**
     * @return bool
     */
    public function isVisibleInHome(): bool
    {
        return $this->in_home === 1 || $this->in_home === true;
    }

    /**
     * @return bool
     */
    public function isValidForAll(): bool
    {
        return $this->is_valid_for_all === 1 || $this->is_valid_for_all === true;
    }

    /**
     * @return bool
     */
    public function isValidForCombos(): bool
    {
        return $this->is_valid_for_combos === 1 || $this->is_valid_for_combos === true;
    }

    /**
     * @return bool
     */
    public function isValidForMemberships(): bool
    {
        return $this->is_valid_for_memberships === 1 || $this->is_valid_for_memberships === true;
    }

    /**
     * @return mixed
     */
    public function brands()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function purchaseDiscountCodes()
    {
        return $this->hasMany(PurchasesDiscountCodes::class, 'discount_codes_id');
    }

    /**
     * @return mixed
     */
    public function purchaseDiscountCodesApplied()
    {
        return $this->purchaseDiscountCodes()->where('applied', 1);
    }

    /**
     * @return bool
     */
    public function isValidNow(): bool
    {
        /**
         * @var Carbon $startDate
         */
        $startDate = $this->discount_from;
        $endDate = $this->discount_to;

        if ($startDate && $startDate->isFuture()) {
            return false;
        }
        if ($endDate && $endDate->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasReachedLimit(): bool
    {
        $limit = $this->total_uses;
        if (is_null($limit)) {
            return false;
        }

        $discountApplied = $this->purchaseDiscountCodesApplied()->count();

        return $discountApplied >= $limit;
    }

    /**
     * @param UserProfile $userProfile
     *
     * @return int
     */
    public function purchaseDiscountCodesAppliedByUser(UserProfile $userProfile): int
    {
        return $this
            ->purchaseDiscountCodesApplied()
            ->where('user_profiles_id', $userProfile->id)
            ->count();
    }

    /**
     * @param UserProfile $userProfile
     *
     * @return bool
     */
    public function isValidForUser(UserProfile $userProfile): bool
    {
        $limit = $this->users_uses;//Check general limit
        $purchasesMin = $this->purchases_min;
        $purchasesMax = $this->purchases_max;

        if (!is_null($limit)) {
            $usesByUser = $this->purchaseDiscountCodesAppliedByUser($userProfile);

            if ($usesByUser >= $limit) {
                return false;
            }
        }
        //Check limit for user
        $userPurchases = $userProfile->countPurchasesCompletesInBrand($this->brands_id);
        if (!is_null($purchasesMin) || !is_null($purchasesMax)) {
            if (!is_null($purchasesMin)) {
                if ($userPurchases < $purchasesMin) {
                    return false;
                }
            }
            if (!is_null($purchasesMax)) {
                if ($userPurchases >= $purchasesMax) {
                    return false;
                }
            }
        }

        return true;
    }

    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'discount_codes_credits', 'discount_codes_id', 'credits_id');
    }

    /**
     * @return mixed
     */
    public function categories()
    {
        return $this->belongsToMany(UserCategory::class, 'discount_codes_categories', 'discount_code_id', 'category_id');
    }

    public function isValidForPurchaseItem(Purchasable $purchase_item)
    {
        $brands_id = $purchase_item->brands_id;
        $companies_id = $purchase_item->companies_id;

        if ($this->brands_id !== $brands_id &&
            $this->companies_id !== $companies_id
        )
            return false;

        $discount_credits = $this->credits;

        if ($discount_credits->count() === 0)
            return true;

        $applicable_credits = $discount_credits->whereIn('id', $purchase_item->creditsCollection()
            ->pluck('id')->toArray());

        return $applicable_credits->count() > 0;
    }
}
