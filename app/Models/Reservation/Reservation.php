<?php

namespace App\Models\Reservation;


use App\Models\GafaFitModel;
use App\Models\JsonColumns\JsonColumnTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends GafaFitModel
{
    use SoftDeletes, ReservationsRelationship, JsonColumnTrait;

    protected $dates = [
        'cancelation_dead_line',
        'meeting_start',
    ];
    protected $casts = [
        'cancelled'    => 'boolean',
        'extra_fields' => 'array',
    ];
    protected $fillable = [
        'users_id',
        'user_profiles_id',
        'meetings_id',
        'meeting_start',
        'cancelation_dead_line',
        'rooms_id',
        'locations_id',
        'brands_id',
        'companies_id',
        'staff_id',
        'services_id',
        'memberships_id',
        'credits_id',
        'credits',
        'maps_objects_id',
        'maps_id',
        'meeting_position',
    ];
}
