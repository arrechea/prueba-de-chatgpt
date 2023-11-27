<?php

namespace App\Models\User;

use App\Models\Credit\Credit;
use App\Models\Credit\CreditsUserRelations;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersCredits extends GafaFitModel
{
    use SoftDeletes, CreditsUserRelations;

    protected $casts = [
        'used' => 'boolean',
    ];
    protected $dates = [
        'expiration_date',
    ];

    protected $fillable = [
        'purchases_id',
        'purchase_items_id',
        'user_profiles_id',
        'users_id',
        'credits_id',
        'expiration_date',
        'locations_id',
        'brands_id',
        'companies_id',
    ];

}
