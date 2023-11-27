<?php

namespace App\Models;

use App\Librerias\Catalog\Metable;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Service\Servicetrait;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends GafaFitModel
{
    use TraitConImagen, Servicetrait, SoftDeletes, JsonColumnTrait;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'pic',
        'category',
        'status',
    ];
    protected $casts = [
        'hide_in_home' => 'boolean',
        'extra_fields' => 'array',
    ];
}
