<?php

namespace App\Librerias\Permissions;

use App\Librerias\Catalog\Tables\GafaFit\CatalogRol;
use App\Librerias\Permissions\Role\RoleRelations;
use App\Librerias\Permissions\Role\RoleTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Silber\Bouncer\Database\Role
{
    use RoleRelations, RoleTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'level',
        'entity_type',
        'entity_id',
        'type',
    ];

    /**
     * The abilities relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function abilities()
    {
        $relation = $this->morphToMany(
            Ability::class,
            'entity',
            'permissions'
        );

        return Models::scope()->applyToRelation($relation);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rol()
    {
        return $this->hasOne(CatalogRol::class, 'id');
    }
}
