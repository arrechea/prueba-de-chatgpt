<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 12/02/2019
 * Time: 12:25
 */

namespace App\Models\DiscountCode;


use App\Models\GafaFitModel;

class DiscountCodesCredits extends GafaFitModel
{
    protected $table = 'discount_codes_credits';

    protected $fillable = [
        'credits_id',
        'discount_codes_id',
    ];
}
