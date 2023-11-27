<?php

namespace App\Models\Membership;

use App\Interfaces\IsProduct;
use App\Models\Purchase\Purchasable;
use App\Models\User\UseUserCategory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Purchasable implements IsProduct
{
    use MembershipRelationship, SoftDeletes, UseUserCategory;
    protected $casts = [
        'hide_in_home' => 'boolean',
    ];

}
