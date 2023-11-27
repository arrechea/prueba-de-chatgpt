<?php

namespace App\Events\Gympass;

use Conekta\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewBookingRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $event_data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $event_data)
    {
        $this->event_data = $event_data;
        \Log::info('booking requested');
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

    /**
     * @return array
     */
    public function getEventData(): array
    {
        return $this->event_data;
    }
}
