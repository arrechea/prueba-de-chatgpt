<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 01:10 PM
 */

namespace App\Models\Payment;


use App\Models\GafaFitModel;

class BrandPaymentType extends GafaFitModel
{
    protected $table = 'brands_payment_types';
    protected $fillable = [
        'brands_id',
        'payment_types_id',
        'config',
        'front',
        'back',
    ];
}
