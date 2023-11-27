<?php

namespace App\Models\Purchase;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;

class PurchasesDiscountCodes extends GafaFitModel
{
    use PurchaseDiscountCodesTrait;
    protected $table = 'purchase_discount_codes';

}
