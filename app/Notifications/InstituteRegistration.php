<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class InstituteRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user     = null;
    protected $email    = null;
    protected $password = null;
    protected $institute_name = null;

    public function __construct(User $user, $email, $password, $institute_name)
    {
        $this->user = $user;
        $this->email = $email;
        $this->password = $password;
        $this->institute_name = $institute_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
        ->view(getTheme().'::emails.new-user-registration',
                    [
                      'user_name'       => $this->user->getUserTitle(),
                      'email'           => $this->email,
                      'password'        => $this->password,
                      'institute_name'  => $this->institute_name
                  ]);



    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
