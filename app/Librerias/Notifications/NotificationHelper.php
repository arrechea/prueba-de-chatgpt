<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wisquimas
 * Date: 28/09/2020
 * Time: 01:14 PM
 */

namespace App\Librerias\Notifications;


use App\Models\Location\Location;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * @param Location|null $location
     * @param               $mail
     */
    static public function detectCCInNotifications(Location $location = null, MailMessage &$mail)
    {
        Log::info("Inicio de comprobacion de necesidad de CC");

        if ($location && $location->locationNeedResendEmails()) {
            $newMail = $location->email??null;
            Log::info("Mail NECESITA CC y envia a {$newMail}");
            if ($newMail) {
                $mail->cc($newMail);
            }
        } else {
            Log::info("Mail NO necesita CC");
        }
    }
}
