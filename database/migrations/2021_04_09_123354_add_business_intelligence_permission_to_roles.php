<?php

use App\Librerias\Permissions\Ability;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\Permission;
use App\Librerias\Permissions\Role;
use Illuminate\Database\Migrations\Migration;

class AddBusinessIntelligencePermissionToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = Role::whereNull('owner_type')
            ->whereNull('owner_id')
            ->whereHas('permissions.ability', function ($q) {
                $q->where('abilities.name', '<>', 'all');
            })
            ->where('name', '<>', 'front-desk')
            ->with('permissions')
            ->orderBy('id', 'asc')
            ->get();

        $abilities = Ability::where('name', LibListPermissions::MENU_BUSINESS_INTELLIGENCE)->get();

        if ($roles->count() && $abilities->count()) {
            foreach ($roles as $role) {
                Permission::insert(
                    $abilities->map(function ($ability) use ($role) {
                        return [
                            'ability_id'  => $ability->id,
                            'entity_id'   => $role->id,
                            'entity_type' => Role::class,
                        ];
                    })->toArray()
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
