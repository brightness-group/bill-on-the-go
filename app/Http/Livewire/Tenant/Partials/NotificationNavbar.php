<?php

namespace App\Http\Livewire\Tenant\Partials;

use App\Notifications\Admin\CalculateActualOperatingTimeJobFails;
use App\Notifications\Admin\CalculateActualOperatingTimeJobSuccess;
use App\Notifications\Admin\ComputeDashboardWidgetsJobFails;
use App\Notifications\Admin\ComputeDashboardWidgetsJobSuccess;
use App\Notifications\Admin\ConnectionRecoveryJobFails;
use App\Notifications\Admin\CustomerOperationTimeExceededNotify;
use App\Notifications\Admin\InviteUserMailSendNotify;
use App\Notifications\Admin\LivetrackConnectionsRecoveredNotify;
use App\Notifications\Admin\RefreshTokenJobProcessedNotify;
use App\Notifications\Admin\RefreshTokenScheduleOnFailNotify;
use App\Notifications\Admin\RefreshTokenScheduleOnSucessNotify;
use App\Notifications\Admin\RetrieveFromAPIFails;
use App\Notifications\Admin\RetrieveFromAPISuccess;
use App\Notifications\Admin\TariffOverlappedNotify;
use App\Notifications\Admin\TariffOverviewConflictNotify;
use App\Notifications\Admin\TimeOverlappedNotify;
use App\Notifications\InviteUser;
use Livewire\Component;

class NotificationNavbar extends Component
{
    public $notifications;

    public $listNotifications = [];

    public $countRead;
    public $countUnread;

    public $isUnread = false;
    public $imgUrl = '';

    public $type = '';
    public $message = '';

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public function mount()
    {
        $read = [];
        $unread = [];
        foreach ($this->notifications as $key => $item) {
            if ($item[0]->unread()) {
                $unread = array_merge($unread,['type'=>$key,'count'=>count($item),'isUnread'=>true]);
                $unread = $this->buildTypeContainerImg($item[0], $unread);
                if (key_exists('email',$item[0]->data)) {
                    $unread = array_merge($unread, ['id' => $item[0]->id, 'email' => $item[0]->data['email'], 'username' => $item[0]->data['username'], 'message' => $item[0]->data['message']]);
                }
                if (key_exists('email',$item[0]->data)) {
                    $unread = array_merge($unread, ['id' => $item[0]->id, 'email' => $item[0]->data['email'], 'username' => $item[0]->data['username'], 'message' => $item[0]->data['message']]);
                }
                $this->listNotifications[$key] = $unread;
            } else {
                $read = array_merge($read,['type'=>$key,'count'=>count($item),'isUnread'=>false]);
                $read = $this->buildTypeContainerImg($item[0], $read);
                if (key_exists('email',$item[0]->data))
                    $read = array_merge($read, ['id' => $item[0]->id, 'email' => $item[0]->data['email'], 'username' => $item[0]->data['username'], 'message' => $item[0]->data['message']]);
                $this->listNotifications[$key] = $read;
            }
        }
    }

    public function render()
    {
        return view('livewire.tenant.partials.notification-navbar',[
            'list'=>$this->listNotifications
        ]);
    }

    public function buildTypeContainerImg($notification,$merged):array
    {
        if (is_a(InviteUser::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/email-send-success.jpg']);
        } elseif (is_a(InviteUserMailSendNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/mail-send.png']);
        } elseif (is_a(RefreshTokenScheduleOnSucessNotify::class, $notification->type, true)) {
            $merged = array_merge($merged,['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(RefreshTokenScheduleOnFailNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-failure.jpg']);
        } elseif (is_a(RetrieveFromAPISuccess::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-database.png']);
        } elseif (is_a(RetrieveFromAPIFails::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-failure.jpg']);
        } elseif (is_a(CalculateActualOperatingTimeJobFails::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-failure.jpg']);
        } elseif (is_a(ComputeDashboardWidgetsJobFails::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-failure.jpg']);
        } elseif (is_a(ConnectionRecoveryJobFails::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/job-failure.jpg']);
        }elseif (is_a(CalculateActualOperatingTimeJobSuccess::class, $notification->type, true)) {
            $merged = array_merge($merged,['imgUrl' => 'images/icon/scheduleSuccess.png']);
        }elseif (is_a(ComputeDashboardWidgetsJobSuccess::class, $notification->type, true)) {
            $merged = array_merge($merged,['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(TimeOverlappedNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(TariffOverlappedNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(TariffOverviewConflictNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(CustomerOperationTimeExceededNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/scheduleSuccess.png']);
        } elseif (is_a(LivetrackConnectionsRecoveredNotify::class, $notification->type, true)) {
            $merged = array_merge($merged, ['imgUrl' => 'images/icon/scheduleSuccess.png']);
        }
        return $merged;
    }

    public function markAsRead($id, $type = null)
    {
        $this->emitTo('tenant.user-notifications-navbar','markAsRead',$id);
        $this->emitSelf('refresh');
        if ($type == 'todo') {
            return $this->redirectRoute('app.todo');
        }
    }
}
