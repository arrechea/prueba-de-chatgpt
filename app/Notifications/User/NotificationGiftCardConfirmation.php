<?php

namespace App\Notifications\User;

use App\Models\Purchase\PurchaseGiftCard;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationGiftCardConfirmation extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $profile;
    private $giftCard;
    private $purchase;

    /**
     * Create a new notification instance.
     *
     * @param PurchaseGiftCard $giftCard
     * @param UserProfile      $user
     */
    public function __construct(PurchaseGiftCard $giftCard, UserProfile $user)
    {
        $this->giftCard = $giftCard;
        $this->profile = $user;
        $this->purchase = $giftCard->purchase;
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

        $title = __('giftcards.GiftCardConfirmation', [], $lang);

        $text = __('giftcards.ConfirmationText', [
            'brand' => $brand->name,
        ], $lang);

        $mail->view('emails.purchases.thank-purchase', [
            'title'             => $title,
            'user'              => $this->giftCard->user_profile ?? null,
            'purchase'          => $this->purchase ?? null,
            'logo_link'         => $confirm_info->logo_link ?? null,
            'store_link'        => $confirm_info->store_link ?? null,
            'confirmation_text' => $confirm_info->giftcards_text ?? $text,
            'copyright'         => $brand->copyright ?? '',
            'social_links'      => $brand->social_links()->render() ?? '',
            'logo'              => $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogo()),
            'background'        => $confirm_info->background_img ?? $this->getDefaultBackground(),
            'items'             => $this->purchase->items ?? [],
            'lang'              => $lang,
            'giftcard'          => $this->giftCard,
            'date'              => new Carbon($this->giftCard->redempted_at) ?? null,
            'is_gift_card'      => true,
        ]);

        $mail->subject(__('giftcards.GiftCardMailTitle', [], $lang));

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
