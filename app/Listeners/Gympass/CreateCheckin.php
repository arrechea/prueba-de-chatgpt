<?php

namespace App\Listeners\Gympass;

use App\Events\Gympass\CheckinCreated;
use App\Librerias\Gympass\Helpers\GympassAPICheckinFunctions;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCheckin
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
     * @param CheckinCreated $event
     *
     * @return void
     */
    public function handle(CheckinCreated $event)
    {
        $event_data = $event->getEventData();
        GympassAPICheckinFunctions::createCheckinFromEvent($event_data);
    }
}
