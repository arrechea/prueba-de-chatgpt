<?php

namespace App\Events\UserProfile;

use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Models\User\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfileUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $profile;
    public $previous_profile;

    /**
     * Create a new event instance.
     *
     * @param UserProfile $previous_profile
     * @param             $profile
     */
    public function __construct(UserProfile $previous_profile, CatalogUserProfile $profile)
    {
        $this->profile = $profile;
        $this->previous_profile = $previous_profile;
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
