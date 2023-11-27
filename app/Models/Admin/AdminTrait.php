<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 27/03/18
 * Time: 09:55
 */

namespace App\Models\Admin;

use App\Admin;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdminProfile;
use App\Librerias\Helpers\LibRoute;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Hash;

trait AdminTrait
{
    /**
     * @return mixed
     */
    public function profile()
    {
        return $this->hasMany(AdminProfile::class, 'admins_id');
    }

    /**
     * @return mixed
     */
    public function profile_catalog()
    {
        return $this->hasOne(CatalogAdminProfile::class, 'admins_id');
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return mixed
     */
    public function companies()
    {
        return $this->hasMany(Company::class, 'admins_id');
    }

    /**
     * @return null|AdminProfile
     */
    public function getProfileInThisCompany()
    {
        $companyActiva = LibRoute::getCompany(request());
        $perfilDeCompany = null;
        if ($companyActiva) {
            //Si estamos logueando a company usaremos esto
            $perfilDeCompany = $this->profile->filter(function ($perfil) use ($companyActiva) {
                return $perfil->companies_id === $companyActiva->id && $perfil->status === 'active';
            })->first();
        }

        return $perfilDeCompany;
    }

    /**
     * @return null|AdminProfile
     */
    public function getForzeProfileInThisCompany()
    {
        $companyActiva = LibRoute::getCompany(request());
        $perfilDeCompany = null;
        if ($companyActiva) {
            //Si estamos logueando a company usaremos esto
            $perfilDeCompany = $this->profile->filter(function ($perfil) use ($companyActiva) {
                return $perfil->companies_id === $companyActiva->id;
            })->first();
        }

        return $perfilDeCompany;
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'id','id');
    }
}
