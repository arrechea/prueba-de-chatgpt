<?php

namespace App\Models\Meeting;

use App\Librerias\Catalog\Metable;
use App\Models\GafaFitModel;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Service;
use App\Models\Staff\Staff;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends GafaFitModel
{
    use MeetingTrait, SoftDeletes, JsonColumnTrait;

    protected $dates = [
        'start_date',
        'end_date',
    ];
    protected $casts = [
        'meeting_max_reservation' => 'integer',
        'extra_fields'            => 'array',
    ];

    protected $fillable = [
        'companies_id',
        'brands_id',
        'locations_id',
        'rooms_id',
        'staff_id',
        'services_id',
        'start_date',
        'end_date',
        'description',
        'color',
        'details',
        'capacity',
    ];
}
