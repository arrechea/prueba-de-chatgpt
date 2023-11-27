<?php

namespace App\Notifications\User;

use App\Librerias\Notifications\NotificationHelper;
use App\Models\Reservation\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationReservationCancelled extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $profile;
    private $reservation;

    /**
     * Create a new notification instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->profile = $reservation->user;
        $this->reservation = $reservation;

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

        $brand = $this->mailer = $this->reservation->brand ?? null;

        $cancel_info = $brand->mailReservationCancel ?? null;

        $service = $this->reservation->service;

        $mail->view('emails.reservation.reservation-cancelled', [
            'user'             => $this->profile ?? null,
            'reservation'      => $this->reservation ?? null,
            'reservation_link' => $cancel_info->reservation_link ?? null,
            'logo_link'        => $cancel_info->logo_link ?? null,
            'copyright'        => $brand->copyright ?? '',
            'social_links'     => $brand->social_links()->render() ?? '',
            'logo'             => $cancel_info->logo ?? ($brand->pic ?? $this->getDefaultLogoWhite()),
            'background'       => $cancel_info->background_img ?? $this->getDefaultBackground(),
            'lang'             => $brand->language->slug ?? 'en',
            'service_name'     => $service && $service->parent_id ? ($service->parentService->name ?? '') : ($service->name ?? ''),
        ]);

        $mail->subject(__('cancel-reservation.ReservationCancelation'));

        $this->setFrom($mail);
        NotificationHelper::detectCCInNotifications(($this->reservation->location??null), $mail);

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

    public function getProfile()
    {
        return $this->profile;
    }
}
