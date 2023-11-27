<?php

namespace App\Notifications\User;

use App\Models\User\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedByImportPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable, NotificationInfoTrait;

    /**
     * @var UserProfile
     */
    private $profile;
    /**
     * @var string|null
     */
    private $password;

    /**
     * Create a new notification instance.
     *
     * @param UserProfile $profile
     */
    public function __construct(UserProfile $profile, string $password = null)
    {
        $this->profile = $profile;
        $this->password = $password;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage);

        $company = $this->mailer = $this->profile->company;
        $info = $company->mailImportUser ?? null;
        $profile = $this->getProfile();
        $password = $this->getPassword();

        $content = $info->content['content'] ?? '';

        $search = [
            '{name}',
            '{fullname}',
            '{email}',
            '{password}',
        ];

        $name = $profile->first_name;
        $fullname = $profile->first_name . ' ' . $profile->last_name;
        $email = $profile->email;

        $replace = [
            $name,
            $fullname,
            $email,
            $password,
        ];

        $content = str_replace($search, $replace, $content);

        $mail->view('emails.users.import-user', [
            'logo_link'  => $info->logo_link ?? null,
            'logo'       => $info->logo ?? ($company->pic ?? asset('logo/ic_whatshot_48px.svg')),
            'background' => $info->background_img ?? asset('images/emails/aurora.png'),
            'title'      => $info->content['title'] ?? '',
            'subtitle'   => $info->content['subtitle'] ?? '',
            'content'    => $content,
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

    /**
     * @return UserProfile
     */
    public function getProfile(): UserProfile
    {
        return $this->profile;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
