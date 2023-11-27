<?php

namespace App\Models\Brand;

use App\Interfaces\HasChildLevels;
use App\Interfaces\HasParentsLevels;
use App\Interfaces\Linkable;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Brand extends GafaFitModel implements Linkable, HasChildLevels, HasParentsLevels
{
    use SoftDeletes, BrandRelationships;
    protected $table = 'brands';
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
        return route('admin.company.brand.dashboard', [
            'company' => $this->company,
            'brand'   => $this,
        ]);
    }

    /**
     * Relation uses for permissions
     *
     * @return Collection
     */
    function childsLevels(): Collection
    {
        $locations = $this->locations;

        $childs = new Collection();
        $locations->each(function ($location) use ($childs) {
            $childs->push($location);
        });

        return $childs;
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

        return $parents;
    }

    /**
     * @return bool
     */
    public function needWaiver(): bool
    {
        return $this->waiver_forze;
    }

    public function social_links()
    {
        return VistasGafaFit::view('emails.common.social-links', [
            'brand' => $this,
        ]);
    }
}
