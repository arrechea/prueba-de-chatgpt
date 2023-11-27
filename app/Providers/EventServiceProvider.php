<?php

namespace App\Providers;

use App\Events\Gympass\BookingCancelled;
use App\Events\Gympass\CheckinCreated;
use App\Events\Gympass\NewBookingRequested;
use App\Events\Gympass\TriggerGympassApprovalEmail;
use App\Events\Purchases\GiftCardRedeemed;
use App\Events\Purchases\PurchaseCreated;
use App\Events\Reservations\ReservationByInvitationCreated;
use App\Events\Reservations\ReservationCancelled;
use App\Events\Reservations\ReservationCreated;
use App\Events\Subscriptions\PaymentCreated;
use App\Events\Subscriptions\PaymentError;
use App\Events\UserProfile\ProfileCreated;
use App\Events\UserProfile\ProfileUpdated;
use App\Events\Waitlist\WaitlistCanceled;
use App\Events\Waitlist\WaitlistCreated;
use App\Listeners\Gympass\CreateCheckin;
use App\Listeners\Gympass\ProcessBookingCancellation;
use App\Listeners\Gympass\RespondToBookingRequest;
use App\Listeners\Gympass\SendGympassEmail;
use App\Listeners\Gympass\UpdateSlotAfterCancellation;
use App\Listeners\Gympass\UpdateSlotAfterReservationCreated;
use App\Listeners\Purchases\SendConfirmGiftCardEmail;
use App\Listeners\Purchases\SendConfirmPurchaseEmail;
use App\Listeners\Reservations\SendInvitationEmail;
use App\Listeners\Reservations\SendReservationCancelledEmail;
use App\Listeners\Reservations\SendReservationConfirmationEmail;
use App\Listeners\Subscriptions\SendSubscriptionConfirmationMail;
use App\Listeners\Subscriptions\SendSubscriptionErrorMail;
use App\Listeners\UserProfile\LogVerificationEmailResent;
use App\Listeners\UserProfile\SendWelcomeEmail;
use App\Listeners\UserProfile\UpdateEmailInMailchimp;
use App\Listeners\Waitlist\SendWaitlistCancellationEmail;
use App\Listeners\Waitlist\SendWaitlistConfirmationEmail;
use App\Listeners\Waitlist\WaitlistReservationCancelled;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ReservationCancelled::class           => [
            SendReservationCancelledEmail::class,
            WaitlistReservationCancelled::class,
            UpdateSlotAfterCancellation::class,
        ],
        ReservationCreated::class             => [
            SendReservationConfirmationEmail::class,
            UpdateSlotAfterReservationCreated::class,
        ],
        PurchaseCreated::class                => [
            SendConfirmPurchaseEmail::class,
        ],
        ProfileCreated::class                 => [
            SendWelcomeEmail::class,
        ],
        NotificationSent::class               => [
            LogVerificationEmailResent::class,
        ],
        GiftCardRedeemed::class               => [
            SendConfirmGiftCardEmail::class,
        ],
        WaitlistCreated::class                => [
            SendWaitlistConfirmationEmail::class,
        ],
        WaitlistCanceled::class               => [
            SendWaitlistCancellationEmail::class,
        ],
        ProfileUpdated::class                 => [
            UpdateEmailInMailchimp::class,
        ],
        PaymentCreated::class                 => [
            SendSubscriptionConfirmationMail::class,
        ],
        PaymentError::class                   => [
            SendSubscriptionErrorMail::class,
        ],
        ReservationByInvitationCreated::class => [
            SendInvitationEmail::class,
        ],
        NewBookingRequested::class            => [
            RespondToBookingRequest::class,
        ],
        BookingCancelled::class               => [
            ProcessBookingCancellation::class,
        ],
        CheckinCreated::class                 => [
            CreateCheckin::class,
        ],
        TriggerGympassApprovalEmail::class    => [
            SendGympassEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
