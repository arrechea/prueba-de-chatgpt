<?php

namespace App\Models\gafafit;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitConImagen;

class Settings extends Model
{
    use TraitConImagen;

    protected $table = 'settings';


    /**
     * Asignacion massiva
     *
     * @var array
     */
    protected $fillable = [
        'meta_value', 'meta_key',
    ];
}
