<?php

namespace App\Models\GympassCheckin;

use App\Models\GafaFitModel;
use App\Models\JsonColumns\JsonColumnTrait;

class GympassCheckin extends GafaFitModel
{
    use JsonColumnTrait, GympassCheckinTrait;

    protected $table = 'gympass_checkin';

    protected $casts = [
        'request_data'  => 'array',
        'response_data' => 'array',
        'extra_fields'  => 'array',
        'errors'        => 'array',
    ];

    protected $dates = [
        'response_time',
        'request_time',
    ];
}
