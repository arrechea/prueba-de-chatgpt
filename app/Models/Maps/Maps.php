<?php

namespace App\Models\Maps;

use App\Models\GafaFitModel;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maps extends GafaFitModel
{
    use MapsRelations, SoftDeletes, TraitConImagen;
}
