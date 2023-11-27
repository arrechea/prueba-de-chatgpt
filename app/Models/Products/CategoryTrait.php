<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/03/2019
 * Time: 10:36
 */

namespace App\Models\Products;


trait CategoryTrait
{
    public function products()
    {
        return $this->hasMany(Product::class, 'product_categories_id');
    }

    public function productCount(){
        $rel=$this->products();

        return $rel->selectRaw($rel->getForeignKey().', count(*) as count')->groupBy($rel->getForeignKey());
    }
}
