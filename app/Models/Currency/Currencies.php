<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'prefijo',
        'sufijo',
        'code3',
    ];
}
