<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 30/04/2018
 * Time: 09:49 AM
 */

namespace App\Models\Offer;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferPossibility extends GafaFitModel
{
    use SoftDeletes;

    protected $table = 'offers_possibilities';

    protected $fillable = [
        'name',
        'model',
        'companies_id',
        'brands_id',
    ];
}
