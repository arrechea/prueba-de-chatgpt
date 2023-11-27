<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 11/06/18
 * Time: 18:24
 */

namespace App\Models\Purchase;

use App\Events\Purchases\PurchaseCreated;
use App\Interfaces\IsProduct;
use App\Librerias\Money\LibMoney;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Currency\Currencies;
use App\Models\DiscountCode\DiscountCode;
use App\Models\Location\Location;
use App\Models\Payment\PaymentType;
use App\Models\Subscriptions\SubscriptionsPayment;
use App\Models\User\UserProfile;
use Carbon\Carbon;

trait PurchaseRelation
{
    static public $statusPending = 'pending';

    private $currency_fields = [
        'subtotal',
        'iva',
        'total',
    ];

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getIsGiftCardAttribute($value)
    {
        return $value === 1 || $value === true;
    }

    public function getHasDiscountCodeAttribute($value)
    {
        return $value === 1 || $value === true;
    }

    /**
     * @return bool
     */
    public function isGiftCard()
    {
        return $this->is_gift_card === true || $this->has_discount_code === 1;
    }

    /**
     * @return mixed
     */
    public function giftCard()
    {
        return $this->hasOne(PurchaseGiftCard::class, 'purchases_id');
    }

    public function hasDiscountCode()
    {
        return $this->has_discount_code === true || $this->has_discount_code === 1;
    }

    public function discountCode()
    {
        return $this->hasOne(PurchasesDiscountCodes::class, 'purchases_id');
    }

    /**
     * @param string $code
     */
    public function addGiftCard(string $code)
    {
        $now = Carbon::now();

        PurchaseGiftCard::insert([
            'code'         => $code,
            'purchases_id' => $this->id,
            'locations_id' => $this->locations_id,
            'brands_id'    => $this->brands_id,
            'companies_id' => $this->companies_id,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    /**
     * @param DiscountCode $discountCode
     */
    public function addDiscountCode(DiscountCode $discountCode)
    {
        $now = Carbon::now();

        $purchaseDiscountCode = new PurchasesDiscountCodes();
        $purchaseDiscountCode->discount_codes_id = $discountCode->id;
        $purchaseDiscountCode->code = $discountCode->code;
        $purchaseDiscountCode->purchases_id = $this->id;
        $purchaseDiscountCode->applied = false;
        $purchaseDiscountCode->locations_id = $this->locations_id;
        $purchaseDiscountCode->brands_id = $this->brands_id;
        $purchaseDiscountCode->companies_id = $this->companies_id;
        $purchaseDiscountCode->user_profiles_id = $this->user_profiles_id;
        $purchaseDiscountCode->admin_profiles_id = $this->admin_profiles_id;
        $purchaseDiscountCode->users_id = $this->users_id;
        $purchaseDiscountCode->short_description = $discountCode->short_description;
        $purchaseDiscountCode->discount_type = $discountCode->discount_type;
        $purchaseDiscountCode->discount_number = $discountCode->discount_number;
        $purchaseDiscountCode->discount_from = $discountCode->discount_from;
        $purchaseDiscountCode->discount_to = $discountCode->discount_to;
        $purchaseDiscountCode->created_at = $now;
        $purchaseDiscountCode->updated_at = $now;
        //calculate discount
        $purchaseDiscountCode->discount_total = $this->calculateDiscountOfTheDiscountCode($purchaseDiscountCode);
        $purchaseDiscountCode->save();
    }

    /**
     * @param PurchasesDiscountCodes $discountCode
     *
     * @return float|int
     */
    public function calculateDiscountOfTheDiscountCode(PurchasesDiscountCodes $discountCode)
    {
        $subtotal = $this->getSubtotal();
        $type = $discountCode->discount_type;
        $discountNumber = (float)($discountCode->discount_number);
        if ($discountNumber > 0) {
            switch ($type) {
                case 'price':
                    return $discountNumber;
                    break;
                case 'percent':
                    return ($discountNumber * $subtotal) / 100;
                    break;
            }
        }

        return 0;
    }

    /**
     * @param IsProduct $product
     * @param float     $cantity
     *
     * @return PurchaseItems
     */
    public function addItem(IsProduct $product, float $cantity = 1): PurchaseItems
    {
        $item = new PurchaseItems();

        $item->purchases_id = $this->id;
        $item->buyed_type = get_class($product);
        $item->buyed_id = $product->id;
        $item->quantity = $cantity;

        $item->locations_id = $this->locations_id;
        $item->brands_id = $this->brands_id;
        $item->companies_id = $this->companies_id;

        $item->admin_profiles_id = $this->admin_profiles_id;
        $item->user_profiles_id = $this->user_profiles_id;
        $item->users_id = $this->users_id;

        $item->item_name = $product->name;
        if ($product->price_final) {
            $item->item_discount = ($product->price_final - $product->price);
            $item->item_price_final = $product->price_final * $cantity;
        } else {
            $item->item_discount = 0;
            $item->item_price_final = $product->price * $cantity;
        }

        $item->item_price = $product->price;


        if (is_int($product->credits)) {
            $item->item_credits = $product->credits * $cantity;
        }
        $item->credits_id = $product->credits_id;


        $item->save();

        return $item;
    }

    /**
     * @param bool $save
     */
    public function updateTotals($save = true)
    {
        $subtotal = $this->getSubtotal();
        $discount = 0;
        if ($this->hasDiscountCode()) {
            $discountCode = $this->discountCode;
            if ($discountCode) {
                $discount += $this->calculateDiscountOfTheDiscountCode($discountCode);
            }
        }
        if ($discount < 0) {
            $discount = 0;
        }

        $total = $subtotal - $discount;

        $this->subtotal = $subtotal;
        $this->discount = $discount;
        $this->total = $total > 0 ? $total : 0;

        if ($save) {
            $this->save();
        }
    }

    /**
     * @return int|float
     */
    public function getSubtotal()
    {
        $items = $this->items;
        $total = 0;

        if ($items->count() > 0) {
            $items->each(function ($item) use (&$total) {
                $total += $item->item_price_final;
            });
        }

        return $total;
    }

    /**
     * @return mixed
     */
    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function admin_profile()
    {
        return $this->belongsTo(AdminProfile::class, 'admin_profiles_id')->withTrashed();
    }

    /**
     *
     */
    public function items()
    {
        return $this->hasMany(PurchaseItems::class, 'purchases_id');
    }

    /**
     * @return mixed
     */
    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_types_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    /**
     * @return bool
     */
    public function needPayment(): bool
    {
        return $this->total > 0 && $this->isPending();
    }

    /**
     * @return bool
     */
    public function dontNeedPayment(): bool
    {
        return !$this->needPayment();
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === $this::$statusPending;
    }

    /**
     *
     */
    public function activateGiftCard()
    {
        if ($this->isGiftCard()) {
            /**
             * @var PurchaseGiftCard $giftCard
             */
            $giftCard = $this->giftCard;
            if ($giftCard) {
                $giftCard->activate();
            }
        }
    }

    /**
     *
     */
    public function applyDiscountCode()
    {
        if ($this->hasDiscountCode()) {
            /**
             * @var PurchasesDiscountCodes $purchaseDiscountCode
             */
            $purchaseDiscountCode = $this->discountCode;
            if ($purchaseDiscountCode) {
                $purchaseDiscountCode->apply();
            }
        }
    }

    /**
     * assignToUser only if is pending
     *
     * @param bool $subscription
     */
    public function assignToUser($subscription = false)
    {
        if ($this->isPending()) {
            if (!$this->isGiftCard()) {
                //only assign if not giftCard
                $items = $this->items;
                if ($items->count() > 0) {
                    $items->each(function ($item) {
                        /**
                         * @var PurchaseItems $item
                         */
                        $item->assignToUser();
                    });
                }
            } else {
                //is giftcard so, active giftCard
                $this->activateGiftCard();
            }
            if ($this->hasDiscountCode()) {
                $this->applyDiscountCode();
            }

            $this->status = 'complete';
            $this->save();
            if ($this instanceof Purchase && !$subscription) {
                try {
                    event(new PurchaseCreated($this, UserProfile::find($this->user_profiles_id)));
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id')->withTrashed();
    }

    public function currency()
    {
        return $this->belongsTo(Currencies::class, 'currencies_id');
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
        $currency = $this->currency;

        return LibMoney::currencyFormat($currency, floatval($this->$field));
    }

    public function isComplete()
    {
        return $this->status === 'complete';
    }

    public function subscription_payments()
    {
        return $this->hasMany(SubscriptionsPayment::class, 'purchases_id');
    }

    public function active_subscription_payment()
    {
        return $this->subscription_payments()
            ->where([
                ['status', 'complete'],
                ['renewal_time', '>', Carbon::now()],
            ]);
    }
}
