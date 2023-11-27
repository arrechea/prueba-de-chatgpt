<?php

namespace App\Models\Credit;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends GafaFitModel
{
    use SoftDeletes, CreditsRelationship;

    protected $table = 'credits';
}
