<?php

namespace App\Models\Catalogs;

use App\Models\GafaFitModel;

class CatalogsFieldsValues extends GafaFitModel
{
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'table',
        'model_id',
        'catalogs_groups_id',
        'catalogs_fields_id',
        'catalogs_groups_index',
        'catalogs_fields_index',
        'value',
    ];

    public function group()
    {
        return $this->belongsTo(CatalogGroup::class, 'catalogs_groups_id');
    }

    public function catalog()
    {
        return $this->group()->catalog();
    }

    public function field()
    {
        return $this->belongsTo(CatalogField::class, 'catalogs_fields_id');
    }
}
