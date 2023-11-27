<?php

namespace App\Models\User;

use App\Models\GafaFitModel;
use App\Models\Membership\Membership;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersMemberships extends GafaFitModel
{
    use SoftDeletes, UserMembershipTrait;

    protected $fillable = [
        'purchases_id',
        'purchase_items_id',
        'user_profiles_id',
        'users_id',
        'memberships_id',
        'expiration_date',
        'reservations_limit',
        'reservations_limit_daily',
        'locations_id',
        'brands_id',
        'companies_id',
//        'created_at',
//        'updated_at',
    ];
    protected $appends = [
        'remainings_reservations',
    ];
    protected $dates = [
        'expiration_date',
    ];
}
