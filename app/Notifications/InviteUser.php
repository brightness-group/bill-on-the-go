<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tenancy\Facades\Tenancy;

class InviteUser extends Notification implements ShouldQueue
{
    use Queueable;

    public $isTenantEnviroment;
    public $company;
    public $user;
    public $token;
    public $asset_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($isTenantEnviroment, $company, $user, $admin, $asset_url)
    {
        $this->isTenantEnviroment = $isTenantEnviroment;
        $this->company = $company;
        $this->user = $user;
        $this->token = Str::random(60);
        $this->asset_url = $asset_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url($this->asset_url.'/invitation-receive/'.$this->user->id.'/'.$this->token);
        $data = [
            'url' => $url,
            'asset_url' => $this->asset_url,
            'tenantEnv' => $this->isTenantEnviroment,
            'tenant_name' => !empty($this->company->name) ? $this->company->name : (APP_EDITION == 'bdgo' ? 'Bdgo' : ucfirst(APP_EDITION)),
        ];
        return (new MailMessage)
            ->subject(__('locale.Invitation'))
            ->markdown('mail.invite.user', $data);
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
            'invitation_token' => $this->token,
            'username' => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}
