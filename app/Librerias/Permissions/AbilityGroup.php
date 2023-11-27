<?php

namespace App\Librerias\Permissions;

use Illuminate\Database\Eloquent\Model;

class AbilityGroup extends Model
{
    protected $table = 'ability_groups';
    protected $fillable = [
        'name',
        'order',
    ];

    public function abilities()
    {
        return $this->hasMany(Ability::class, 'ability_groups_id')->orderBy('abilities.order');
    }
}
