<?php

namespace App\Notifications\Subscription;

use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Subscriptions\SubscriptionsPayment;
use App\Models\User\UserProfile;
use App\Notifications\User\NotificationInfoTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationSubscriptionCreated extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $payment;
    private $profile;

    /**
     * Create a new notification instance.
     *
     * @param SubscriptionsPayment $payment
     * @param UserProfile          $profile
     */
    public function __construct(SubscriptionsPayment $payment, UserProfile $profile)
    {
        $this->payment = $payment;
        $this->profile = $profile;

        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage);

        $subscription = $this->payment->subscription;

        $brand = $this->mailer = $subscription->brand ?? null;

        $confirm_info = $brand->mailSubscriptionPaymentConfirm ?? null;

        $recurrent_payment = $this->payment->subscription->recurrent_payment;

        $lang = $brand->language->slug ?? 'en';

        $title = __('subscriptions.SubscriptionConfirmation', [], $lang);

        $mail->view('emails.subscriptions.confirmation-payment', [
            'title'             => $title,
            'user'              => $this->profile ?? null,
            'purchase'          => $this->payment->purchase ?? null,
            'subscription'      => $subscription,
            'logo_link'         => $confirm_info->logo_link ?? null,
            'store_link'        => $confirm_info->store_link ?? null,
            'confirmation_text' => $confirm_info->confirmation_text ?? null,
            'copyright'         => $brand->copyright ?? '',
            'social_links'      => $brand->social_links()->render() ?? '',
            'logo'              => $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogo()),
            'background'        => $confirm_info->background_img ?? $this->getDefaultBackground(),
            'items'             => $this->payment->items ?? [],
            'lang'              => $lang,
            'date'              => $this->payment->created_at_timezoned ?? null,
            'is_gift_card'      => false,
            'payment_type'      => $recurrent_payment->payment_type,
        ]);

        $mail->subject(__('subscriptions.SubscriptionConfirmation'));

        $this->setFrom($mail);

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
