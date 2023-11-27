<?php

namespace App\Listeners\UserProfile;

use App\Events\UserProfile\ProfileCreated;
use App\Librerias\Users\LibUserProfiles;
use App\Notifications\User\NotificationWelcome;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProfileCreated $event
     *
     * @return void
     */
    public function handle(ProfileCreated $event)
    {
        $user = $event->profile ?? null;

        $user->notify(new NotificationWelcome($user, $user->token ?? ''));
    }
}
