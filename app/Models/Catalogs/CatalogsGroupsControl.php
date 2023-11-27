<?php

namespace App\Models\Catalogs;

use App\Models\GafaFitModel;

class CatalogsGroupsControl extends GafaFitModel
{
    protected $table = 'catalogs_groups_controls';

    protected $fillable = [
//        'companies_id',
//        'brands_id',
        'catalogs_groups_id',
        'section',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CatalogGroup::class, 'catalogs_groups_id');
    }
}
