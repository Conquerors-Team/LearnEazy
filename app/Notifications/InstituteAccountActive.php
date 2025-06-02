<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class InstituteAccountActive extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user = null;
    protected $status_message = null;
    protected $ins_name = null;
    protected $comments = null;

    public function __construct(User $user, $status_message, $ins_name, $comments)
    {
        $this->user = $user;
        $this->status_message = $status_message;
        $this->ins_name = $ins_name;
        $this->comments = $comments;
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
        ->view(getTheme().'::emails.institute-activated',
                    ['user_name' => $this->user->getUserTitle(),
                      'status_message'=>$this->status_message,
                      'ins_name'=>$this->ins_name,
                      'comments'=>$this->comments
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
