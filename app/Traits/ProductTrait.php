<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 30/04/2018
 * Time: 05:49 PM
 */

namespace App\Traits;

use Carbon\Carbon;

/**
 * Trait ProductTrait
 *
 * Se usa para funcionalidades en comun y para expandir propiedades
 *
 * @package App\Traits
 */
trait ProductTrait
{

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function getArrayableItems(array $values)
    {
        //Fake attributes
        if (!in_array('has_discount', $this->appends)) {
            $this->appends[] = 'has_discount';
        }
        if (!in_array('price_final', $this->appends)) {
            $this->appends[] = 'price_final';
        }

        //Dates
        if (!in_array('discount_from', $this->dates)) {
            $this->dates[] = 'discount_from';
        }
        if (!in_array('discount_to', $this->dates)) {
            $this->dates[] = 'discount_to';
        }

        return parent::getArrayableItems($values);
    }

    /**
     *
     * @return bool
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->hasDiscount();
    }

    /**
     * @return bool
     */
    public function hasDiscount(): bool
    {
        //Check discount number
        $number = $this->discount_number;
        //Check discount dates
        //      $from = $this->discount_from;
//        $to = $this->discount_to;
        $from = is_string($this->discount_from) ? Carbon::parse($this->discount_from, $this->getTimezone()) : $this->discount_from;
        $to = is_string($this->discount_to) ? Carbon::parse($this->discount_to, $this->getTimezone()) : $this->discount_to;
        //    dd($from, $to);

        if (!is_null($number)) {
            $aplicaPasado = true;
            $aplicaFuturo = true;

            if (!is_null($from)) {
                $aplicaPasado = $from->isPast();
            }
            if (!is_null($to)) {
                $aplicaFuturo = $to->isFuture();
            }

            return $aplicaPasado && $aplicaFuturo;
        }

        return false;
    }

    /**
     * @return float|mixed
     * @internal param $price
     *
     */
    public function getPriceFinalAttribute(): float
    {
        $price = $this->price;
        if ($this->hasDiscount() && $price > 0) {
            $discountType = $this->discount_type;
            $number = $this->discount_number;

            switch ($discountType) {
                case 'price':
                    $price -= $number;
                    break;
                case 'percent':
                    $price -= (($number * $price) / 100);
                    break;
            }
            if ($price < 0) {
                $price = 0;
            }
        }

        return round($price, 2);
    }
}
