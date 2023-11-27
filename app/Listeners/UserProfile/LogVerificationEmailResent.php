<?php

namespace App\Listeners\UserProfile;

use App\Models\Log\ResendVerificationRequest;
use App\Notifications\User\NotificationWelcomeResend;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogVerificationEmailResent implements ShouldQueue
{
    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('notifications');
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent $event
     *
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        if ($event->notification instanceof NotificationWelcomeResend) {
            $email = $event->notifiable->email;
            $companies_id = $event->notifiable->companies_id;
            $date = Carbon::now();
            ResendVerificationRequest::updateOrCreate([
                'email'        => $email,
                'companies_id' => $companies_id,
            ], [
                'date' => $date,
            ]);
        }

    }
}
