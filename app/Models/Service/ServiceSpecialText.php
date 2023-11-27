<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/05/2018
 * Time: 12:49 PM
 */

namespace App\Models\Service;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSpecialText extends Model
{
    use ServiceSpecialTextTrait, SoftDeletes;

    protected $table = 'service_special_texts';

    protected $fillable = [
        'services_id',
        'companies_id',
        'brands_id',
        'slug',
        'order',
        'title',
        'description',
    ];
}
