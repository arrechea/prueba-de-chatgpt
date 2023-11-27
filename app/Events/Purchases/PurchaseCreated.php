<?php

namespace App\Events\Purchases;

use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PurchaseCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchase;
    public $profile;

    /**
     * Create a new event instance.
     *
     * @param Purchase    $purchase
     * @param UserProfile $profile
     */
    public function __construct(Purchase $purchase, UserProfile $profile)
    {
        $this->purchase = $purchase;
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
