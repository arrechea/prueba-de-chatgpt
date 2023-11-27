<?php

namespace App\Listeners\Purchases;

use App\Events\Purchases\GiftCardRedeemed;
use App\Notifications\User\NotificationGiftCardConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmGiftCardEmail
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
     * @param  GiftCardRedeemed $event
     *
     * @return void
     */
    public function handle(GiftCardRedeemed $event)
    {
        $user = $event->profile;
        $user->notify(new NotificationGiftCardConfirmation($event->giftCard, $user));
    }
}
