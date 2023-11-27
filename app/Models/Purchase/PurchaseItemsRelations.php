<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 01/08/2018
 * Time: 09:32 AM
 */

namespace App\Models\Purchase;


use App\Librerias\Money\LibMoney;
use App\Models\Combos\Combos;
use App\Models\Credit\Credit;
use App\Models\Membership\Membership;
use App\Models\User\UserProfile;

trait PurchaseItemsRelations
{

    private $currency_fields = [
        'item_discount',
        'item_price',
        'item_price_final',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function buyed()
    {
        return $this->morphTo();
    }

    /**
     * @return bool
     */
    public function isAssigned(): bool
    {
        return $this->assigned === true;
    }

    /**
     * @return bool
     */
    public function isNotAssigned(): bool
    {
        return !$this->isAssigned();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id')->withTrashed();
    }

    /**
     * Assign purchase to users
     *
     * @param UserProfile|null $profile
     */
    public function assignToUser(UserProfile $profile = null)
    {
        if ($this->isNotAssigned()) {
            /**
             * @var UserProfile $userProfile
             */
            $userProfile = $profile ?? $this->userProfile;
            $expiration = null;
            $buyed = $this->buyed;
            if ($buyed && $expirationDays = $buyed->expiration_days) {
                $expiration = (int)$expirationDays;
            }
            if ($buyed) {
                if ($buyed instanceof Combos) {
                    $userProfile->addCredits($this->credits_id, $expiration ?? 30, $this->brands_id, $this->locations_id, $this->item_credits, $this->purchases_id, $this->id);
                } else if ($buyed instanceof Membership) {
                    //add membership
                    $userProfile->addMembership($buyed, $expiration ?? 30, $this->brands_id, $this->locations_id, $this->purchases_id, $this->id);
                }
            } else {
                //Productos que tengan creditos asociados
                $userProfile->addCredits($this->credits_id, $expiration ?? 30, $this->brands_id, $this->locations_id, $this->item_credits, $this->purchases_id, $this->id);
            }

            $this->assigned = true;
            $this->save();
        }
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id')->withTrashed();
    }

    /**
     * Se le provee a la función el campo para el que se quiera obtener el número con el
     * prefijo de dinero. Si éste está en el array 'currency_fields', se regresa un
     * string de tipo: $99.99
     *
     * @param string $field
     *
     * @return string
     */
    public function printWithPrefix(string $field)
    {
//        if (in_array($field, $this->currency_fields, true)) {
//            return ($this->purchase->currency->prefijo ?? '') .
//                (number_format($this->$field ?? 0, 2));
//        }

        $currency = $this->purchase->currency;

        return LibMoney::currencyFormat($currency, floatval($this->$field));
    }

    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credits_id')->withTrashed();
    }
}
