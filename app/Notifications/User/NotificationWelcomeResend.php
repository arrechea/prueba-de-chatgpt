<?php

namespace App\Notifications\User;

use App\Librerias\Colors\LibColors;
use App\Messages\SendGridMessage;
use App\Models\User\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationWelcomeResend extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    private $profile;
    private $token;

    /**
     * Create a new notification instance.
     *
     * @param UserProfile $profile
     * @param string      $token
     */
    public function __construct(UserProfile $profile, string $token)
    {
        $this->profile = $profile;
        $this->token = $token;

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
        return ['sendgrid'];
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

        $company = $this->mailer = $this->profile->company ?? null;
        $info = $company->mailWelcomeInfo ?? null;

        $mail->view('emails.users.welcome', [
            'profile'    => $this->profile ?? null,
            'logo_link'  => $info->logo_link ?? null,
            'logo'       => $info->logo ?? ($company->pic ?? $this->getDefaultLogo()),
            'background' => $info->background_img ?? $this->getDefaultBackground(),
            'text_1'     => $info->text_1 ?? '',
            'text_2'     => $info->text_2 ?? '',
            'copyright'  => $company->copyright ?? '',
            'token'      => $this->token ?? '',
            'lang'       => $company->language->slug ?? 'en',
            'email'      => $this->profile->email ?? '',
            'mainColor'  => LibColors::get('color_black', $company),
        ]);

        $mail->subject(__('mails.welcome'));

        $this->setFrom($mail);

        return $mail;
    }

    /**
     * Get the SendGrid representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return SendGridMessage
     */
    public function toSendGrid($notifiable)
    {
        $company = $this->mailer = $this->profile->company ?? null;
        $info = $company->mailWelcomeInfo ?? null;

        $data = [
            'subject'    => __('mails.welcome'),
            'first_name' => $this->profile->first_name ?? null,
            'last_name'  => $this->profile->last_name ?? null,
            'company'    => $company->name ?? null,
            'logo_link'  => $info->logo_link ?? null,
            'logo'       => $info->logo ?? ($company->pic ?? $this->getDefaultLogo()),
            'background' => $info->background_img ?? $this->getDefaultBackground(),
            'text_1'     => $info->text_1 ?? '',
            'text_2'     => $info->text_2 ?? '',
            'copyright'  => $company->copyright ?? '',
            'email'      => $this->profile->email ?? '',
        ];

        //   d-af6d01ee9d124e72a119cdf1b9840009' OLD ID - BUQ - Front Welcome
        return (new SendGridMessage('d-80ee8f0ed1744579bae9832fbf78c5cb'))
            ->data($data)
            ->from($this->mailer->mail_from ?? 'noreply@buq.partners', $this->mailer->name_from ?? 'BUQ')
            ->to($this->profile->email, $this->profile->email);
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
