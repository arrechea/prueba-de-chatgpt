<?php

namespace App\Listeners\UserProfile;

use App\Events\UserProfile\ProfileUpdated;
use App\Librerias\MailchimpSDK\LibMailchimpSDK;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEmailInMailchimp
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
     * @param  ProfileUpdated $event
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(ProfileUpdated $event)
    {
        $profile = $event->profile;
        $prev = $event->previous_profile;
        $current_email = $prev->email;
        $new_email = $profile->email;
        $company = $profile->company;
        $sdk = new LibMailchimpSDK($company);
        if ((!$profile->isActive() && $prev->isActive()) ||
            (!$profile->isVerified() && $prev->isVerified())) {
            $sdk->unsubscribe($current_email);
        }
        if ($profile->isActive() && $profile->isVerified()) {
            if ((!$prev->isVerified()) ||
                (!$prev->isActive())) {
                $sdk->subscribe($new_email);
            } else if ($new_email !== $current_email) {
                $sdk->updateEmail($current_email, $new_email);
            }
        }
    }
}
