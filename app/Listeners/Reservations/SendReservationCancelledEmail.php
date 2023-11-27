<?php

namespace App\Listeners\Reservations;

use App\Events\Reservations\ReservationCancelled;
use App\Notifications\User\NotificationReservationCancelled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReservationCancelledEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReservationCancelled $event
     *
     * @return void
     */
    public function handle(ReservationCancelled $event)
    {
        $user = $event->profile ?? null;
        $user->notify(new NotificationReservationCancelled($event->reservation));
    }
}
