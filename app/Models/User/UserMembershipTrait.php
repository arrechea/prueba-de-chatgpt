<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 07/02/2019
 * Time: 16:11
 */

namespace App\Models\User;


use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\UserInformation\CatalogUserActiveMemberships;
use App\Librerias\GafaPay\LibGafaPay;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Membership\Membership;
use App\Models\Purchase\Purchase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait UserMembershipTrait
{
    /**
     * @return int
     */
    public function getRemainingsReservationsAttribute()
    {
        if ($this->hasReservationsLimit()) {
            $profile = $this->profile;
            $memberships_id = $this->memberships_id;
            $limit = $this->reservations_limit;
            if ($profile) {
                $ownedReservationsWithThisMembership = $profile
                    ->reservationsWithoutCancelled()
                    ->where('memberships_id', $memberships_id)
                    ->count();

                return ((int)$limit) - $ownedReservationsWithThisMembership;
            }
        }

        return null;
    }

    /**
     * @return BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'memberships_id')->withTrashed();
    }

    /**
     * @return bool
     */
    public function hasReservationsLimit(): bool
    {
        return !is_null($this->reservations_limit) || !is_null($this->reservations_limit_daily);
    }

    /**
     * @return mixed
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id')
            ->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id')
            ->withTrashed();
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

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    /**
     * @return bool
     * @throws ValidationException
     */
    public function cancelMembership(): bool
    {
        $purchase = $this->purchase;
        $request = new Request();
        $request->merge([
            'id' => $this->id,
        ]);
        $return = false;
        if ($purchase && $purchase->subscription) {
            $brand = $this->brand;

            $response = LibGafaPay::cancelSubscription($purchase->subscription, $brand->gafapay_client_id, $brand->gafapay_client_secret);
            if (!empty($response)) {

                $model = CatalogFacade::delete($request, CatalogUserActiveMemberships::class);
                $return = !!$model;
            }
        } else {
            $model = CatalogFacade::delete($request, CatalogUserActiveMemberships::class);
            $return = !!$model;
        }

        return $return;
    }
}
