<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 11/06/18
 * Time: 18:14
 */

namespace App\Interfaces;


interface IsProduct
{

    /**
     * @param array $values
     *
     * @return mixed
     */
    function getArrayableItems(array $values);

    /**
     *
     * @return bool
     */
    public function getHasDiscountAttribute();

    /**
     * @return bool
     */
    public function hasDiscount();

    /**
     * @return float|mixed
     * @internal param $price
     *
     */
    public function getPriceFinalAttribute();
}
