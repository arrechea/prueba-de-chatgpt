<?php

namespace App;

use App\Librerias\Permissions\Role;
use Illuminate\Database\Eloquent\Model;

class AssignedRol extends Model
{
    protected $table = 'assigned_roles';
    public $timestamps = false;
    protected $fillable = [
        'role_id',
        'entity_id',
        'entity_type',
        'assigned_id',
        'assigned_type',
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
