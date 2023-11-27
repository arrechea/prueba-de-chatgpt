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

class NotificationInvitationReservation extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $email;
    private $name;
    private $reservation;
    private $profile;

    /**
     * Create a new notification instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation, string $email, string $name = null)
    {
        $this->reservation = $reservation;
        $this->profile = $reservation->user;
        $this->email = $email;
        $this->name = $name !== null ? $name : $email;

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
        $reservation_link = isset($confirm_info->reservation_link) && ($confirm_info->reservation_link) ? $confirm_info->reservation_link . '?reservations_id=' . $reservationId : null;
        $social_links = $brand->social_links()->render() ?? '';
        $logo = $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogoWhite());
        $background = $confirm_info->background_img ?? $this->getDefaultBackground();
        $lang = $brand->language->slug ?? 'en';
        $service_name = isset($service->parent_id) ? ($service->parentService->name ?? '') : ($service->name ?? '');
        $date = $this->reservation->getCreatedAtTimezonedAttribute();
        $position = LibMapFunctions::getPositionText($this->reservation);

        $mail->view('emails.reservation.reservation-invitation', [
            'user'             => $this->profile ?? null,
            'name'             => $this->name ?? '',
            'title'            => $confirm_info->text ?? null,
            'reservation'      => $this->reservation ?? null,
            'reservation_link' => $reservation_link,
            'logo_link'        => $confirm_info->logo_link ?? null,
            'copyright'        => $brand->copyright ?? '',
            'social_links'     => $social_links,
            'logo'             => $logo,
            'background'       => $background,
            'lang'             => $lang,
            'service_name'     => $service_name,
            'date'             => $date,
            'position'         => $position,
        ]);

        $mail->subject(__('confirm-reservation.ReservationConfirmation'));

        $this->setFrom($mail);
        Log::info("Envio de correo a {$this->profile->email} de reserva para invitado");
        NotificationHelper::detectCCInNotifications(($this->reservation->location ?? null), $mail);
        Log::info("CC del mail de invitacion final:" . print_r($mail->cc, true));

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
