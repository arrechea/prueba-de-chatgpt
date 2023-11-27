<?php

namespace App\Mail;

use App\Models\Brand\Brand;
use App\Models\Reservation\Reservation;
use App\Notifications\User\NotificationInfoTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationReservation extends Mailable
{
    use Queueable, SerializesModels, NotificationInfoTrait;

    private $reservation;
    private $profile;
    private $email;
    private $name;

    /**
     * Create a new message instance.
     *
     * @param string      $email
     * @param string      $name
     * @param Reservation $reservation
     */
    public function __construct(string $email, string $name, Reservation $reservation)
    {
        $this->reservation = $reservation;
        $this->profile = $reservation->user;
        $this->email = $email;
        $this->name = $name;

        $this->onQueue('notifications');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reservation = $this->reservation;
        $brand = $this->mailer = $reservation && $reservation->brands_id ? Brand::find($reservation->brands_id) : null;

        $confirm_info = $brand->mailInvitationConfirm ?? null;
        $service = $this->reservation->service;
        $reservationId = $this->reservation->id;
        $email = $this->email;
        $reservation_link = isset($confirm_info->reservation_link) && ($confirm_info->reservation_link) ? $confirm_info->reservation_link . '?reservations_id=' . $reservationId : null;
        $social_links = $brand->social_links()->render()??'';
        $logo = $confirm_info->logo ?? ($brand->pic ?? $this->getDefaultLogoWhite());
        $background = $confirm_info->background_img ?? $this->getDefaultBackground();
        $lang = $brand->language->slug ?? 'en';
        $service_name = isset($service->parent_id) ? ($service->parentService->name ?? '') : ($service->name ?? '');
        $date = $this->reservation->getCreatedAtTimezonedAttribute();

        $address = config('mail.address');
        $name = $brand->name;

        if ($address) {
            $this->from($address, $name);
        } else {
            $this->from('noreply@meetingmanager.pro', 'Meeting Manager');
        }

        return $this
            ->view('emails.reservation.reservation-invitation', [
                'user'             => $this->profile ?? null,
                'name'             => $this->name??'',
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
            ])->to($email)
            ->onQueue('notifications');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}
