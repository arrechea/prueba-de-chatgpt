<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 19/04/18
 * Time: 09:29
 */

namespace App\Models\User;


use App\Librerias\Helpers\LibRoute;
use Illuminate\Support\Facades\Hash;

trait UserLoginTrait
{
    /**
     * @return mixed
     */
    public function getAuthPassword()
    {
        $companyActiva = LibRoute::getCompany(request());
        if ($companyActiva) {
            $profile = $this->profile;
            if ($profile) {
                //Si estamos logueando a company usaremos esto
                $perfilDeCompany = $profile->filter(function ($perfil) use ($companyActiva) {
                    return $perfil->companies_id === $companyActiva->id;
                })->first();
            }
            if (isset($perfilDeCompany)) {
                //Contrasenna de esa
                return $perfilDeCompany->password;
            } else {
                //Imposible de loguear
                return str_random(60);
            }
        }

        return $this->password;
    }

    /**
     * Busqueda de user por name en passport
     *
     * @param $username
     *
     * @return mixed
     */
    public function findForPassport($username)
    {
        $companyActiva = LibRoute::getCompany(request());
        if ($companyActiva) {

            return $this->whereHas('profile', function ($query) use ($username, $companyActiva) {
                $query->where('email', $username);
                $query->where('companies_id', $companyActiva->id);
            })->first();
        } else {
            //Sin company
            return $this->where('email', $username)->first();
        }
    }

    /**
     * @param $password
     *
     * @return mixed
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->getAuthPassword());
    }
}
