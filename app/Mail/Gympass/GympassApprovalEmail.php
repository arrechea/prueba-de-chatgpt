<?php

namespace App\Mail\Gympass;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GympassApprovalEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $gym_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($gym_id)
    {
        $this->gym_id = $gym_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $secret = config('gympass.webhook_secret', null);
        $gym_id = $this->gym_id;
        $webhook_url = route('gympass.webhooks.requested');
        $address = 'ayuda@buq.mx';

        return $this->view('emails.gympass.approval')
            ->with([
                'gym_id'      => $gym_id,
                'secret'      => $secret,
                'webhook_url' => $webhook_url,
            ])->subject("Nuevo Gimnasio Onboarding $gym_id")->from($address);
    }
}
