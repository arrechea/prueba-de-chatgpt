<?php

namespace App\Listeners\Waitlist;

use App\Events\Waitlist\WaitlistCanceled;
use App\Notifications\User\NotificationWaitlistCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWaitlistCancellationEmail
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
     * @param  WaitlistCanceled $event
     *
     * @return void
     */
    public function handle(WaitlistCanceled $event)
    {
        if ($event->waitlist->status === 'returned') {
            $user = $event->profile ?? null;
            $user->notify(new NotificationWaitlistCanceled($event->waitlist));
        }
    }
}
