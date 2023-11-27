<?php

namespace App\Notifications\User;

use App\Librerias\Map\LibMapFunctions;
use App\Librerias\Notifications\NotificationHelper;
use App\Models\Reservation\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotificationReservationConfirmation extends Notification implements ShouldQueue
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
        $this->reservation = $reservation;
        $this->profile = $reservation->user;

        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage);

        $brand = $this->mailer = $this->reservation->brand ?? null;

        $confirm_info = $brand->mailReservationConfirm ?? null;
        $service = $this->reservation->service;
        $reservationId = $this->reservation->id;
        $position = LibMapFunctions::getPositionText($this->reservation);

        $mail->view('emails.reservation.reservation-confirmation', [
            'user'             => $this->profile ?? null,
            'reservation'      => $this->reservation ?? null,
            'reservation_link' => isset($confirm_info->reservation_link) && $confirm_info->reservation_link ? $confirm_info->reservation_link . '?reservations_id=' . $reservationId : null,
            'logo_link'        => $confirm_info->logo_link ?? null,
            'copyright'        => $brand->copyright ?? '',
            'social_links'     => $brand->social_links()->render() ?? '',
            'logo'             => $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogoWhite()),
            'background'       => $confirm_info->background_img ?? $this->getDefaultBackground(),
            'lang'             => $brand->language->slug ?? 'en',
            'service_name'     => $service && $service->parent_id ? ($service->parentService->name ?? '') : ($service->name ?? ''),
            'date'             => $this->reservation->getCreatedAtTimezonedAttribute(),
            'position'        => $position,
        ]);

        $mail->subject(__('confirm-reservation.ReservationConfirmation'));

        $this->setFrom($mail);
        Log::info("Envio de correo a {$this->profile->email} de reserva");
        NotificationHelper::detectCCInNotifications(($this->reservation->location ?? null), $mail);
        Log::info("CC del mail final:" . print_r($mail->cc, true));

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
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
