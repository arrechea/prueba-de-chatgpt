<?php

namespace App\Models\Purchase;

use App\Models\Combos\Combos;
use App\Models\GafaFitModel;
use App\Models\Membership\Membership;
use App\Models\User\UserProfile;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItems extends GafaFitModel
{
    use SoftDeletes, PurchaseItemsRelations;

}
