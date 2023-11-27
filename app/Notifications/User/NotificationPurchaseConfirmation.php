<?php

namespace App\Notifications\User;

use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationPurchaseConfirmation extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $purchase;
    private $profile;

    /**
     * Create a new notification instance.
     *
     * @param Purchase    $purchase
     * @param UserProfile $user
     */
    public function __construct(Purchase $purchase, UserProfile $user)
    {
        $this->purchase = $purchase;
        $this->profile = $user;

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

        $brand = $this->mailer = $this->purchase->brand ?? null;

        $confirm_info = $brand->mailPurchaseConfirm ?? null;

        $lang = $brand->language->slug ?? 'en';

        $title = __('confirm-order.OrderConfirmation', [], $lang);

        $mail->view('emails.purchases.thank-purchase', [
            'title'             => $title,
            'user'              => $this->purchase->user_profile ?? null,
            'purchase'          => $this->purchase ?? null,
            'logo_link'         => $confirm_info->logo_link ?? null,
            'store_link'        => $confirm_info->store_link ?? null,
            'confirmation_text' => $confirm_info->confirmation_text ?? null,
            'copyright'         => $brand->copyright ?? '',
            'social_links'      => $brand->social_links()->render() ?? '',
            'logo'              => $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogo()),
            'background'        => $confirm_info->background_img ?? $this->getDefaultBackground(),
            'items'             => $this->purchase->items ?? [],
            'lang'              => $lang,
            'giftcard'          => $this->purchase->giftCard,
            'date'              => $this->purchase->created_at_timezoned ?? null,
            'is_gift_card'      => false,
            'discountCode'      => $this->purchase->discountCode,
        ]);

        $mail->subject(__('confirm-order.ConfirmationOfOrder'));

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
