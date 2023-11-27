<?php

namespace App\Listeners\Reservations;

use App\Events\Reservations\ReservationCreated;
use App\Notifications\User\NotificationReservationConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReservationConfirmationEmail
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
     * @param  ReservationCreated  $event
     * @return void
     */
    public function handle(ReservationCreated $event)
    {
        $user = $event->profile ?? null;
        $user->notify(new NotificationReservationConfirmation($event->reservation));
    }
}
