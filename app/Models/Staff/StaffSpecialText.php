<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/06/2018
 * Time: 12:35 PM
 */

namespace App\Models\Staff;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffSpecialText extends GafaFitModel
{
    use StaffSpecialTextTrait, SoftDeletes;

    protected $table = 'staff_special_texts';
    protected $fillable = [
        'staff_id',
        'companies_id',
        'brands_id',
        'tag',
        'order',
        'title',
        'description',
    ];
}
