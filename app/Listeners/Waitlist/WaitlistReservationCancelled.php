<?php

namespace App\Listeners\Waitlist;

use App\Events\Reservations\ReservationCancelled;
use App\Models\Meeting\Meeting;
use App\Models\Waitlist\Waitlist;
use Illuminate\Contracts\Queue\ShouldQueue;

class WaitlistReservationCancelled implements ShouldQueue
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
     * @param  ReservationCancelled $reservationCancelled
     *
     * @return void
     */
    public function handle($reservationCancelled)
    {
        $reservation = $reservationCancelled->reservation;
        $reservation->load([
            'meetings.awaiting',
        ]);
        /**
         * @var Meeting $meeting
         */
        $meeting = $reservation->meetings;
        /**
         * @var null|Waitlist $waitlistPending
         */
        $waitlistPending = $meeting->awaiting->first();
        if ($waitlistPending) {
            $waitlistPending->approveFromReservation($reservation);
        }
    }
}
