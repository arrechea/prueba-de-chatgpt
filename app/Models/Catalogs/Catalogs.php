<?php

namespace App\Models\Catalogs;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;

class Catalogs extends GafaFitModel
{
    protected $table = 'catalogs';

    protected $fillable=[
        'name',
        'table'
    ];
}
