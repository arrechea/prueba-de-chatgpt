<?php

namespace App\Models\Purchase;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends GafaFitModel
{
    use SoftDeletes, PurchaseRelation;

}
