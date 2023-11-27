<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 02/04/18
 * Time: 16:00
 */

namespace App\Librerias\Models\Role;


use App\Librerias\Permissions\Role;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Support\Facades\DB;

abstract class LibRole
{
    /**
     * @param Role $role
     *
     * @return Role
     */
    static public function clone(Role $role): Role
    {
        $new = $role->replicate();
        $new->push();
        $role->relations = [];

        //load relations on EXISTING MODEL
        $role->load('permissions');

        //re-sync everything
        foreach ($role->permissions as $permiso) {
            $newPermiso = $permiso->replicate();
            $newPermiso->entity_id = $new->id;
            $newPermiso->push();
        }

        return $new;
    }

    public static function cloneCompanyLevel(Role $role, Company $company): Role
    {
        $new = $role->replicate();
        $new->companies_id = $company->id;
        $new->owner_type = Company::class;
        $new->owner_id = $company->id;
        $new->push();
        $role->relations = [];

        //load relations on EXISTING MODEL
        $role->load('permissions');

        $permissions = $role->permissions()->whereHas('ability', function ($q) use ($company) {
            $q->whereNotNull('entity_type');
        })->get();

        //re-sync everything
        foreach ($permissions as $permiso) {
            $newPermiso = $permiso->replicate();
            $newPermiso->entity_id = $new->id;
            $newPermiso->push();
        }

        return $new;
    }
}
