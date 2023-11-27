<?php

namespace App\Listeners\Purchases;

use App\Events\Purchases\PurchaseCreated;
use App\Notifications\User\NotificationPurchaseConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmPurchaseEmail
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
     * @param  PurchaseCreated $event
     *
     * @return void
     */
    public function handle(PurchaseCreated $event)
    {
        $user = $event->profile ?? null;
        $user->notify(new NotificationPurchaseConfirmation($event->purchase, $user));
    }
}
