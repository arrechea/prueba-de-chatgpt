<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 27/04/2018
 * Time: 10:11 AM
 */

namespace App\Models\Offer;


use App\Models\Brand\Brand;
use App\Models\Company\Company;

trait OfferRelations
{
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    public function isActive()
    {
        return (boolean)$this->active;
    }
}
