<?php

namespace App;

use App\Librerias\Permissions\HasRolesAndAbilities;
use App\Models\Admin\AdminLoginTrait;
use App\Models\Admin\AdminTrait;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{

    use Notifiable, HasRolesAndAbilities, TraitConImagen, AdminTrait, SoftDeletes, AdminLoginTrait;

    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pic', 'status', 'designation',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
