<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;
use Tenancy\Facades\Tenancy;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $token;

    public $tries = 10;

    public function __construct($token)
    {
        $this->token = $token;
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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (Tenancy::getTenant()) {
            $url = url(config('app.asset_url') . route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false));
            $expire = config('auth.passwords.tenant.expire');
        }
        else {
            $url = url(config('app.url') . route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false));
            $expire = config('auth.passwords.users.expire');
        }

        return (new MailMessage)
            ->subject(__('locale.Reset Password Notification'))
            ->markdown('mail.reset.password', ['url' => $url, 'expire' => $expire]);
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
