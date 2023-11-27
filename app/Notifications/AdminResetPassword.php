<?php

namespace App\Notifications;

use App\Messages\SendGridMessage;
use App\Notifications\User\NotificationInfoTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends Notification
{
    use Queueable, NotificationInfoTrait;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    /**
     * @var
     */
    private $company;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token, $company)
    {
        $this->token = $token;
        $this->company = $company;
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
        $company = $this->getCompany();
        $link = $company ?
            route('admin.companyLogin.password.resetNow', [
                'token'   => $this->token,
                'company' => $company,
            ]) :
            route('admin.password.resetNow', [
                'token' => $this->token,
            ]);

        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $link)
            ->line('If you did not request a password reset, no further action is required.');
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
        $company = $this->getCompany();
        $this->mailer = $company;

        $link = $company ?
            route('admin.companyLogin.password.resetNow', [
                'token'   => $this->token,
                'company' => $company,
            ]) :
            route('admin.password.resetNow', [
                'token' => $this->token,
            ]);

        $data = [
            "subject" => "Cambio de contraseña",
            "content" => "Presione el siguiente link para cambiar la contraseña",
            "link"    => $link,
        ];

        // 'd-2dfe404657af4fa89df989e4ea4f31b2' OLD ID - BUQ - Admin Reset Password
        return (new SendGridMessage('d-2c7813a77f2d4dbb85e2c0741bd08e7e'))
            ->data($data)
            ->from($this->mailer->mail_from ?? 'noreply@buq.partners', $this->mailer->name_from ?? 'BUQ')
            ->to($notifiable->email, $notifiable->name);
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }
}
