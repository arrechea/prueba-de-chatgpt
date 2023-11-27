<?php

namespace App\Models\Catalogs;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;

class CatalogsFieldsOptions extends GafaFitModel
{
    protected $table='catalogs_fields_options';

    protected $fillable=[
        'value',
        'catalogs_id',
        'catalogs_fields_id',
        'catalogs_groups_id',
        'created_at',
        'updated_at',
    ];
}
