<?php

namespace App\Listeners\Gympass;

use App\Events\Reservations\ReservationCancelled;
use App\Librerias\Gympass\Helpers\GympassAPIBookingFunctions;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSlotAfterCancellation
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
     * @param ReservationCancelled $event
     *
     * @return void
     */
    public function handle(ReservationCancelled $event)
    {
        GympassAPIBookingFunctions::updateAfterReservationCancellation($event->reservation);
    }
}
