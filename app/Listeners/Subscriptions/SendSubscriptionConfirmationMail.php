<?php

namespace App\Listeners\Subscriptions;

use App\Events\Subscriptions\PaymentCreated;
use App\Notifications\Subscription\NotificationSubscriptionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscriptionConfirmationMail implements ShouldQueue
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
     * @param  PaymentCreated $event
     *
     * @return void
     */
    public function handle(PaymentCreated $event)
    {
        $user = $event->profile ?? null;
        $user->notify(new NotificationSubscriptionCreated($event->payment, $user));
    }
}
