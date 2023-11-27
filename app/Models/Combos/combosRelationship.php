<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 30/04/2018
 * Time: 05:49 PM
 */

namespace App\Models\Combos;

use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Service;
use App\Models\User\UserCategory;
use App\Traits\ProductTrait;
use Illuminate\Support\Collection;


trait combosRelationship
{
    use ProductTrait;

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id')->withTrashed();

    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function getPosibleRoles()
    {
        $rolesCompany = $this->company->getPosibleRoles();

        return $rolesCompany->concat($this->roles);
    }

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
        return $this->hasManyThrough(Service::class, CombosServices::class, 'combos_id', 'id', 'id', 'services_id');
    }


    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->hasOne(Credit::class, 'id', 'credits_id')->withTrashed();
    }

    public function creditsCollection()
    {
        return new Collection([$this->credit]);
    }

    /**
     * @return bool
     */
    public function isSubscribable(): bool
    {
        return $this->subscribable === 1 || $this->subscribable;
    }

    /**
     * @return mixed
     */
    public function categories()
    {
        return $this->belongsToMany(UserCategory::class, 'combos_categories', 'combos_id', 'category_id');
    }
}
