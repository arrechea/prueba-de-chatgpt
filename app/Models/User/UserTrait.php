<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/03/2018
 * Time: 11:50 AM
 */

namespace App\Models\User;


use App\Librerias\Helpers\LibRoute;
use App\Models\Location\Location;

trait UserTrait
{
    /**
     * Encripta la contraseña
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Da formato de email
     *
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * Muestra si el perfil está activo
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * Regresa los perfiles del usuario
     *
     * @return mixed
     */
    public function profiles()
    {
        return $this->hasMany(UserProfile::class, 'users_id');
    }

    /**
     * Shortcode
     *
     * @return mixed
     */
    public function profile()
    {
        return $this->profiles();
    }

    /**
     * @param Location|null $location
     *
     * @return UserProfile|null
     */
    public function getProfileInThisCompany(Location $location = null)
    {
        $companyActiva = $location ? $location->company : LibRoute::getCompany(request());

        $perfilDeCompany = null;
        if ($companyActiva) {
            //Si estamos logueando a company usaremos esto
            $perfilDeCompany = $this->profile()
                ->where('user_profiles.companies_id', $companyActiva->id)
                ->where('user_profiles.status', 'active')
                ->first();
        }

        return $perfilDeCompany;
    }

    /**
     * @param $companyId
     *
     * @return mixed
     */
    public function getProfileByCompanyId($companyId)
    {
        //Si estamos logueando a company usaremos esto
        return $this->profile()
            ->where('user_profiles.companies_id', $companyId)
            ->where('user_profiles.status', 'active')
            ->first();
    }

    /**
     * @return null|UserProfile
     */
    public function getForzeProfileInThisCompany()
    {
        $companyActiva = LibRoute::getCompany(request());
        $perfilDeCompany = null;
        if ($companyActiva) {
            //Si estamos logueando a company usaremos esto
            $perfilDeCompany = $this->profile()
                ->where('user_profiles.companies_id', $companyActiva->id)
                ->first();
        }

        return $perfilDeCompany;
    }
}
