<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 16/04/18
 * Time: 17:11
 */

namespace App\Models\Admin;


use App\Librerias\Helpers\LibRoute;
use App\Notifications\AdminResetPassword;

trait AdminLoginTrait
{
    /**
     * @return mixed
     */
    public function getAuthPassword()
    {
        $companyActiva = LibRoute::getCompany(request());
        if ($companyActiva) {
            //Si estamos logueando a company usaremos esto
            $perfilDeCompany = $this->getProfileInThisCompany();
            if ($perfilDeCompany) {
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
     * Send the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token, LibRoute::getCompany(request())));
    }

    /**
     * @return mixed
     */
    private
    function getEmailForNotifications()
    {
        $companyProfile = $this->getProfileInThisCompany();

        return $companyProfile ? $companyProfile->email : $this->email;
    }

    /**
     * @return mixed
     */
    public
    function getEmailForPasswordReset()
    {
        return $this->getEmailForNotifications();
    }

    /**
     * @return mixed
     */
    public
    function routeNotificationForMail()
    {
        return $this->getEmailForNotifications();
    }
}
