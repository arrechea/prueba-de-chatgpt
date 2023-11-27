<?php

namespace App\Events\Purchases;

use App\Models\Purchase\PurchaseGiftCard;
use App\Models\User\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GiftCardRedeemed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $giftCard;
    public $profile;

    /**
     * Create a new event instance.
     *
     * @param PurchaseGiftCard $giftCard
     * @param UserProfile      $profile
     */
    public function __construct(PurchaseGiftCard $giftCard, UserProfile $profile)
    {
        $this->giftCard = $giftCard;
        $this->profile = $profile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
