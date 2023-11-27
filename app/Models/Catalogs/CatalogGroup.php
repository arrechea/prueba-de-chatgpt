<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/11/2018
 * Time: 11:08
 */

namespace App\Models\Catalogs;


use App\Models\GafaFitModel;

class CatalogGroup extends GafaFitModel implements InterfaceSpecialTexts
{
    use CatalogsGroupsRelations;
    protected $table = 'catalogs_groups';

    protected $fillable = [
        'name',
        'slug',
        'catalogs_id',
        'companies_id',
        'brands_id',
        'created_at',
        'updated_at',
    ];
}
