<?php

namespace App\Notifications\User;

use App\Models\Waitlist\Waitlist;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationWaitlistCanceled extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $profile;
    private $waitlist;

    /**
     * Create a new notification instance.
     *
     * @param Waitlist $waitlist
     */
    public function __construct(Waitlist $waitlist)
    {
        $this->waitlist = $waitlist;
        $this->profile = $waitlist->profile;

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

        $brand = $this->mailer = $this->waitlist->brand ?? null;

        $cancel_info = $brand->mailWaitlistCancel ?? null;
        $service = $this->waitlist->service;
        $waitlistId = $this->waitlist->id;

        $mail->view('emails.waitlist.waitlist-cancelled', [
            'user'          => $this->profile ?? null,
            'waitlist'      => $this->waitlist ?? null,
            'text'          => $cancel_info->text ?? null,
            'waitlist_link' => ($cancel_info && $cancel_info->waitlist_link) ? $cancel_info->waitlist_link . '?waitlist_id=' . $waitlistId : null,
            'logo_link'     => $cancel_info->logo_link ?? null,
            'copyright'     => $brand->copyright ?? '',
            'social_links'  => $brand->social_links()->render() ?? '',
            'logo'          => $cancel_info->logo ?? ($brand->pic ?? $this->getDefaultLogoWhite()),
            'background'    => $cancel_info->background_img ?? $this->getDefaultBackground(),
            'lang'          => $brand->language->slug ?? 'en',
            'service_name'  => $service && $service->parent_id ? ($service->parentService->name ?? '') : ($service->name ?? ''),
        ]);

        $mail->subject(__('cancel-waitlist.WaitlistCancellation'));

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
