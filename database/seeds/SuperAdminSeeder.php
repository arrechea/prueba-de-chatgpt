<?php

use App\Admin;
use App\AssignedRol;
use App\Librerias\Permissions\Ability;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Permissions\Permission;
use App\Librerias\Permissions\Role;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id'    => 1,
            'name'  => 'superadmin',
            'title' => 'SuperAdmin',
            'type'  => LibPermissions::LEVEL_GAFAFIT,
        ]);
        Ability::create([
            'id'    => 1,
            'name'  => 'all',
            'title' => 'roles.ability-all',
        ]);
        Ability::create([
            'id'          => 2,
            'name'        => 'all',
            'title'       => 'roles.ability-all',
            'entity_type' => Company::class,
        ]);
        Ability::create([
            'id'          => 3,
            'name'        => 'all',
            'title'       => 'roles.ability-all',
            'entity_type' => Brand::class,
        ]);
        Ability::create([
            'id'          => 4,
            'name'        => 'all',
            'title'       => 'roles.ability-all',
            'entity_type' => Location::class,
        ]);
        Permission::create([
            'ability_id'  => 1,
            'entity_id'   => 1,
            'entity_type' => Role::class,
        ]);
        Permission::create([
            'ability_id'  => 2,
            'entity_id'   => 1,
            'entity_type' => Role::class,
        ]);
        Permission::create([
            'ability_id'  => 3,
            'entity_id'   => 1,
            'entity_type' => Role::class,
        ]);
        Permission::create([
            'ability_id'  => 4,
            'entity_id'   => 1,
            'entity_type' => Role::class,
        ]);
        AssignedRol::create([
            'role_id'       => 1,
            'entity_id'     => 1,
            'entity_type'   => Admin::class,
            'assigned_id'   => null,
            'assigned_type' => null,
        ]);
        AssignedRol::create([
            'role_id'       => 1,
            'entity_id'     => 2,
            'entity_type'   => Admin::class,
            'assigned_id'   => null,
            'assigned_type' => null,
        ]);
    }
}
