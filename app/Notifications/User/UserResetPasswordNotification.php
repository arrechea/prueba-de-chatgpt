<?php

namespace App\Notifications\User;

use App\Models\User\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;
    /**
     * @var UserProfile
     */
    private $profile;
    /**
     * @var
     */
    private $token;
    /**
     * @var
     */
    private $return_url;

    /**
     * Create a new notification instance.
     *
     * @param UserProfile $profile
     * @param             $token
     * @param             $return_url
     */
    public function __construct(UserProfile $profile, $token, $return_url)
    {
        $this->profile = $profile;
        $this->token = $token;
        $this->return_url = $return_url;
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

        $company = $this->mailer = $this->profile->company;
        $info = $company->mailForgotPassword ?? null;
        $profile = $this->getProfile();
        $return_url = "{$this->getReturnUrl()}?token={$this->getToken()}&email={$profile->email}";

        $mail->view('emails.users.reset-password', [
            'logo_link'  => $info->logo_link ?? null,
            'logo'       => $info->logo ?? ($company->pic ?? $this->getDefaultLogo()),
            'background' => $info->background_img ?? $this->getDefaultBackground(),
            'text_1'     => $info->text_1 ?? '',
            'text_2'     => $info->text_2 ?? '',
            'return_url' => $return_url,
            'copyright'  => $company->copyright ?? '',
            'lang'       => $company->language->slug ?? 'en',
        ]);

        $mail->subject(__('reset-password.ConfirmReset'));

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

    /**
     * @return UserProfile
     */
    public function getProfile(): UserProfile
    {
        return $this->profile;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->return_url;
    }
}
