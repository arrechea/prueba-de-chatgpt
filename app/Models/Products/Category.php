<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/03/2019
 * Time: 17:22
 */

namespace App\Models\Products;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends GafaFitModel
{
    use SoftDeletes, CategoryTrait;

    protected $table = 'product_categories';
}
