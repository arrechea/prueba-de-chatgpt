<?php

namespace App\Events\Reservations;

use App\Models\Reservation\Reservation;
use App\Models\User\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReservationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;
    public $profile;

    /**
     * Create a new event instance.
     *
     * @param Reservation $reservation
     * @param UserProfile $profile
     */
    public function __construct(Reservation $reservation, UserProfile $profile)
    {
        $this->reservation = $reservation;
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
