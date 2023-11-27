<?php

namespace App\Listeners\Subscriptions;

use App\Events\Subscriptions\PaymentError;
use App\Notifications\Subscription\NotificationSubscriptionError;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscriptionErrorMail implements ShouldQueue
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
     * @param  PaymentError  $event
     * @return void
     */
    public function handle(PaymentError $event)
    {
        $user = $event->profile ?? null;
        $user->notify(new NotificationSubscriptionError($event->payment, $user));
    }
}
