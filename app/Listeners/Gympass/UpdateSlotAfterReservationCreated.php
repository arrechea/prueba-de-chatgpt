<?php

namespace App\Listeners\Gympass;

use App\Events\Reservations\ReservationCreated;
use App\Librerias\Gympass\Helpers\GympassAPISlotsFunctions;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSlotAfterReservationCreated
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
     * @param ReservationCreated $event
     *
     * @return void
     */
    public function handle(ReservationCreated $event)
    {
        $reservation = $event->reservation;
        $meeting = $reservation->meetings;
        GympassAPISlotsFunctions::patchSlotFromMeeting($meeting);
    }
}
