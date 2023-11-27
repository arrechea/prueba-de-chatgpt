<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 09:34 AM
 */

namespace App\Models\Combos;


use App\Models\GafaFitModel;

class CombosServices extends GafaFitModel
{
    protected $table = 'combos_services';
    protected $fillable = [
        'combos_id',
        'services_id',
    ];
}
