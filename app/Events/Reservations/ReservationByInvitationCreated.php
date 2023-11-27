<?php

namespace App\Events\Reservations;

use App\Models\Reservation\Reservation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationByInvitationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $email;
    private $reservation;
    private $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $email, string $name, Reservation $reservation)
    {
        $this->email = $email;
        $this->reservation = $reservation;
        $this->name = $name;
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
