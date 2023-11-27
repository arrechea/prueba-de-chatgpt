<?php

namespace App\Models\DiscountCode;

use App\Models\GafaFitModel;
use App\Models\Purchase\PurchaseDiscountCodesTrait;
use App\Models\User\UseUserCategory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends GafaFitModel
{
    use DiscountRelations, UseUserCategory;
    protected $table = 'discount_codes';

    protected $dates = [
        'discount_from',
        'discount_to',
    ];
}
