<?php

namespace App\Models\Location;

use App\Interfaces\HasParentsLevels;
use App\Interfaces\Linkable;
use App\Librerias\Permissions\Role;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\GafaFitModel;
use App\Models\JsonColumns\JsonColumnTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Location extends GafaFitModel implements Linkable, HasParentsLevels
{
    use SoftDeletes, locationsRelationship, JsonColumnTrait;

    protected $casts = [
        'waiver_forze' => 'boolean',
        'extra_fields' => 'array',
    ];

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.brand.locations.dashboard', [
            'company'  => $this->company,
            'brand'    => $this->brand,
            'location' => $this,
        ]);
    }

    /**
     * Relation uses for permissions
     *
     * @return Collection
     */
    function parentsLevels(): Collection
    {
        $parents = new Collection();
        $parents->push($this->company);
        $parents->push($this->brand);

        return $parents;
    }

    /**
     * @return mixed
     */
    public function getPosibleRoles()
    {
        $rolesBrands = $this->brand->getPosibleRoles();

        return $rolesBrands->concat($this->roles);
    }

    /**
     * Roles
     */
    public function roles()
    {
        return $this->morphMany(Role::class, 'owner')->orderBy('name', 'desc');
    }

    /**
     * @return bool
     */
    public function needWaiver(): bool
    {
        return $this->waiver_forze;
    }
}
