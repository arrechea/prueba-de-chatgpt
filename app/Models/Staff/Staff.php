<?php

namespace App\Models\Staff;

use  App\Librerias\Catalog\Tables\Location\Reservations\StaffRelationship;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends GafaFitModel
{
    use SoftDeletes, StaffRelationship;
    protected $casts = [
        'hide_in_home' => 'boolean',
    ];
}
