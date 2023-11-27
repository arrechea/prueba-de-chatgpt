<?php

namespace App\Listeners\Waitlist;

use App\Events\Waitlist\WaitlistCreated;
use App\Notifications\User\NotificationWaitlistConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWaitlistConfirmationEmail
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
     * @param  WaitlistCreated $event
     *
     * @return void
     */
    public function handle(WaitlistCreated $event)
    {
        if ($event->waitlist->status === 'waiting') {
            $user = $event->profile ?? null;
            $user->notify(new NotificationWaitlistConfirmation($event->waitlist));
        }
    }
}
