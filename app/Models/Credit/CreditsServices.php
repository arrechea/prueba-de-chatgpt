<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 24/05/2018
 * Time: 05:24 PM
 */

namespace App\Models\Credit;


use App\Models\GafaFitModel;

class CreditsServices extends GafaFitModel
{
    protected $table = 'credits_services';

    protected $fillable = [
        'credits_id',
        'services_id',
        'credits',
    ];
}
