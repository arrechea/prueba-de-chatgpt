<?php

namespace App\Listeners\Gympass;

use App\Events\Gympass\BookingCancelled;
use App\Librerias\Gympass\Helpers\GympassAPIBookingFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessBookingCancellation
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param BookingCancelled $event
     *
     * @return void
     */
    public function handle(BookingCancelled $event)
    {
        $event_data = $event->getEventData();
        GympassAPIBookingFunctions::processCancellationRequest($event_data);
    }
}
