<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/05/2018
 * Time: 09:53 AM
 */

namespace App\Models\Credit;


use App\Models\Brand\Brand;
use App\Models\Service;

trait CreditsRelationship
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return mixed
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'credits_services', 'credits_id', 'services_id')->withPivot('credits');
//        return $this->hasManyThrough(Service::class, CreditsServices::class, 'credits_id', 'id', 'id', 'services_id');
    }

    /**
     * @return mixed
     */
    public function brandsc()
    {
        return $this->belongsToMany(Brand::class, 'credits_brand', 'credits_id', 'brands_id')->withPivot('credits');
    }
}
