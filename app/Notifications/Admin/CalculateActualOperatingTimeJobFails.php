<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CalculateActualOperatingTimeJobFails extends Notification
{
    use Queueable;

    public $user;
    public $exception;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$exception)
    {
        $this->user = $user;
        $this->exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'username' => $this->user->name,
            'email' => $this->user->email,
            'message' => 'An error occurs while trying to compute dashboard widgets data as part of a scheduled job.',
            'exception' => $this->exception
        ];
    }
}
