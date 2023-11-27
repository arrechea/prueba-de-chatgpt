<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 4/9/2019
 * Time: 12:36
 */

namespace App\Models\Credit;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditsBrand extends GafaFitModel
{
    use SoftDeletes, CreditsBrandRelations;
    protected $table='credits_brand';

    protected $fillable = [
        'credits_id',
        'brands_id',
        'companies_id',
        'gafa_fit',
    ];

}
