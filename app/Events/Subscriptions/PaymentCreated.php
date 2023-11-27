<?php

namespace App\Events\Subscriptions;

use App\Models\Subscriptions\SubscriptionsPayment;
use App\Models\User\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $profile;

    /**
     * Create a new event instance.
     *
     * @param SubscriptionsPayment $payment
     * @param UserProfile          $profile
     */
    public function __construct(SubscriptionsPayment $payment, UserProfile $profile)
    {
        $this->payment = $payment;
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
