<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 02/04/18
 * Time: 11:10
 */

namespace App\Librerias\Permissions\Role;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;

trait RoleTrait
{
    /**
     * Setea el owner
     */
    public function setOwner()
    {
        $this->owner_type = null;
        $this->owner_id = null;

        if (!is_null($this->locations_id)) {
            $this->owner_type = Location::class;
            $this->owner_id = $this->locations_id;
        } else if (!is_null($this->brands_id)) {
            $this->owner_type = Brand::class;
            $this->owner_id = $this->brands_id;
        } else if (!is_null($this->companies_id)) {
            $this->owner_type = Company::class;
            $this->owner_id = $this->companies_id;
        }
    }
}
