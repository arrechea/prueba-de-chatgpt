<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 06/03/18
 * Time: 09:39
 */

namespace App\Librerias\Permissions;


class Ability extends \Silber\Bouncer\Database\Ability
{
    protected $fillable = [
        'name',
        'title',
        'entity_id',
        'entity_type',
    ];

    public function group()
    {
        return $this->belongsTo(AbilityGroup::class, 'ability_groups_id');
    }
}
