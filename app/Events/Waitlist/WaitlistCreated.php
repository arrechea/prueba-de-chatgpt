<?php

namespace App\Events\Waitlist;

use App\Models\User\UserProfile;
use App\Models\Waitlist\Waitlist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WaitlistCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $waitlist;
    public $profile;

    /**
     * Create a new event instance.
     *
     * @param Waitlist    $waitlist
     * @param UserProfile $profile
     */
    public function __construct(Waitlist $waitlist, UserProfile $profile)
    {
        $this->waitlist = $waitlist;
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
