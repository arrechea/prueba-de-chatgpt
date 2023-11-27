<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 15/03/18
 * Time: 17:15
 */

namespace App\Librerias\Permissions\Role;


use App\Admin;
use App\Librerias\Permissions\Permission;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;

trait RoleRelations
{
    /**
     * Permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->morphMany(Permission::class, 'entity');
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function admins()
    {
        return $this->morphedByMany(Admin::class, 'entity', 'assigned_roles', 'role_id');
    }

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
    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id')->withTrashed();
    }
}
