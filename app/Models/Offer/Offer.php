<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 27/04/2018
 * Time: 10:06 AM
 */

namespace App\Models\Offer;


use App\Models\GafaFitModel;
use App\Models\Offer\OfferRelations;
use App\Traits\TraitConImagen;

class Offer extends GafaFitModel
{
    use OfferRelations, TraitConImagen;

    protected $table = 'offers';

    protected $fillable = [
        'name',
        'companies_id',
        'brands_id',
        'from',
        'to',
        'active',
        'image',
        'type',
        'buy_get_get',
        'buy_get_buy',
        'code',
        'discount_number',
        'discount_type',
        'credits',
        'user_limit',
    ];
}
