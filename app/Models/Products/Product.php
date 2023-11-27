<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/03/2019
 * Time: 17:21
 */

namespace App\Models\Products;

use App\Interfaces\IsProduct;
use App\Models\Purchase\Purchasable;
use Illuminate\Database\Eloquent\SoftDeletes;


use App\Models\GafaFitModel;

class Product extends Purchasable implements IsProduct
{
    protected $table = 'products';

    public function creditsCollection(){
        return false;
    }

    public function getArrayableItems(array $values){
        return $values;
    }

    public function getHasDiscountAttribute(){
        return false;
    }

    public function hasDiscount(){
        return false;
    }

    public function getPriceFinalAttribute(){
        return false;
    }
}
