<?php

namespace App\Models\Maps;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapsObject extends GafaFitModel
{
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function positions()
    {
        return $this->belongsTo(MapsPosition::class, 'maps_positions_id');
    }
}
