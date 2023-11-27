<?php

namespace App\Models\User;

use App\Events\UserProfile\ProfileCreated;
use App\Librerias\Catalog\Metable;
use App\Models\JsonColumns\JsonColumnTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserProfile extends Model implements CanResetPassword
{
    use ProfileTrait, Notifiable, SoftDeletes, JsonColumnTrait;

    protected $table = 'user_profiles';

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'verified'        => 'boolean',
        'blocked_reserve' => 'boolean',
        'custom_fields'   => 'object',
        'extra_fields'    => 'array',
    ];

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
    }

    /**
     * Lanza un evento ProfileCreated al crear un perfil
     */
    public static function boot()
    {
        parent::boot();

        static::created(function (UserProfile $profile) {
            event(new ProfileCreated($profile));
        });
    }
}
