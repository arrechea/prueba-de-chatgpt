<?php

namespace App\Listeners\Reservations;

use App\Events\Reservations\ReservationByInvitationCreated;
use App\Mail\InvitationReservation;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmail
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
     * @param ReservationByInvitationCreated $event
     *
     * @return void
     */
    public function handle(ReservationByInvitationCreated $event)
    {
        $email = $event->getEmail();
        $reservation = $event->getReservation();
        $name = $event->getName();
        Mail::to($email)->send(new InvitationReservation($email, $name, $reservation));
    }
}
