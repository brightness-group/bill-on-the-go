<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\User;
use App\Notifications\Admin\InviteUserMailSendNotify;
use App\Notifications\Admin\RefreshTokenJobProcessedNotify;
use App\Notifications\Admin\RefreshTokenScheduleOnSucessNotify;
use App\Notifications\InviteUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Array_;

class UserNotificationsNavbar extends Component
{

    public $user, $notifications;

    public $counNotifications, $readCount, $unreadCount;


    protected $listeners = [
        'refresh' => '$refresh',
        'markAsRead'
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $this->counNotifications = count($this->user->notifications);

        $notificationsGroupBy = $this->user->notifications->where('read_at','!=',null)->groupBy('type');
        $this->readCount = count($notificationsGroupBy->all());

        $unreadNotificationsGroupBy = $this->user->unreadNotifications->groupBy('type');
        $this->unreadCount = count($unreadNotificationsGroupBy->all());

        return view('livewire.tenant.user-notifications-navbar',[
            'unreadNotifications' => $unreadNotificationsGroupBy->all(),
            'readNotifications' => $notificationsGroupBy->all(),
        ]);
    }

    public function markAllAsRead()
    {
        $this->user->unreadNotifications->markAsRead();
        $this->emitSelf('refresh');
    }

    public function markAsRead($id)
    {
        $unreadNotification = $this->user->notifications()->find($id);
        if($unreadNotification) {
            $unreadNotification->markAsRead();
        }
        $this->emitSelf('refresh');
    }

}
