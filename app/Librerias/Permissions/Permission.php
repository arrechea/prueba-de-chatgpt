<?php

namespace App\Librerias\Permissions;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'ability_id',
        'entity_id',
        'entity_type',
        'forbidden',
        'scope',
    ];

    protected $table = 'permissions';

    public function entity()
    {
        return $this->morphTo();
    }

    public function ability()
    {
        return $this->belongsTo(Ability::class, 'ability_id');
    }
}
