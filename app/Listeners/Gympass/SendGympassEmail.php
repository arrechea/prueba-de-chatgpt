<?php

namespace App\Listeners\Gympass;

use App\Events\Gympass\TriggerGympassApprovalEmail;
use App\Mail\Gympass\GympassApprovalEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendGympassEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TriggerGympassApprovalEmail $event
     *
     * @return void
     */
    public function handle(TriggerGympassApprovalEmail $event)
    {
        $location = $event->getLocation();
        $email = config('gympass.gympass_email', 'argel@gafa.mx');
//        todo: comentar esto
//        $email='argel@gafa.mx';
        if ($email && $email !== '') {
            Mail::to($email)->send(new GympassApprovalEmail($location->getDotValue('extra_fields.gympass.gym_id')));

            $location->setDotValue('extra_fields.gympass.email_sent', 1, true);
        }

        $location->setDotValue('extra_fields.gympass.approval', 0, true);
    }
}
