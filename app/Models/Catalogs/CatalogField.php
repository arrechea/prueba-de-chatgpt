<?php

namespace App\Models\Catalogs;


use App\Models\GafaFitModel;
use App\Traits\TraitConImagen;

class CatalogField extends GafaFitModel implements InterfaceSpecialTexts
{
    use CatalogsFieldsRelations, TraitConImagen;
    protected $table = 'catalogs_fields';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'catalogs_id',
        'catalogs_groups_id',
        'companies_id',
        'brands_id',
        'created_at',
        'updated_at',
        'hidden_in_list',
    ];
}
