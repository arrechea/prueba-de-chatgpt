<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/05/2018
 * Time: 09:50 AM
 */

namespace App\Models\Staff;


use App\Models\GafaFitModel;

class StaffBrands extends GafaFitModel
{
    protected $table = 'staff_brands';
    protected $fillable = [
        'brands_id',
        'staff_id',
    ];
}
