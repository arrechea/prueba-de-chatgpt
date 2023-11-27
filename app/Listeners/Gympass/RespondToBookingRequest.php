<?php

namespace App\Listeners\Gympass;

use App\Events\Gympass\NewBookingRequested;
use App\Librerias\Gympass\Helpers\GympassAPIBookingFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Validation\ValidationException;

class RespondToBookingRequest
{
//    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->onQueue('notifications');
    }


    /**
     * Handle the event.
     *
     * @param NewBookingRequested $event
     *
     * @return void
     * @throws ValidationException
     */
    public function handle(NewBookingRequested $event)
    {
        \Log::info('booking listener');
        $event_data = $event->getEventData();
        GympassAPIBookingFunctions::validateBookingRequest($event_data);
    }
}
