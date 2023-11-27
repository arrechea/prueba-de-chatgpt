<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 03/08/2018
 * Time: 11:25 AM
 */

namespace App\Notifications\User;


use Illuminate\Support\Facades\Mail;

trait NotificationInfoTrait
{
    private $mailer;

    /**
     * Obtiene el logo default que aparece en la esquina superior izquierda de los emails.
     *
     * @return string
     */
    public function getDefaultLogo()
    {
        return asset('logo/ic_whatshot_black_48px.svg');
    }

    public function getDefaultBackground()
    {
        return asset('images/emails/aurora.png');
    }

    public function getDefaultLogoWhite()
    {
        return asset('logo/ic_whatshot_48px.svg');
    }

    public function setFrom(&$mail)
    {
        $mail_from = $this->mailer->mail_from ?? 'noreply@buq.partners';
        $name_from = $this->mailer->name_from ?? 'BUQ';
        $mail->from($mail_from, $name_from);
    }
}
