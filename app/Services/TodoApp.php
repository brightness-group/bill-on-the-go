<?php

namespace App\Services;

use App\Models\Tenant\Notification;
use App\Models\Tenant\Todo;
use App\Models\Tenant\User;
use App\Notifications\Admin\CustomerOperationTimeExceededNotify;
use App\Notifications\Admin\LivetrackConnectionsRecoveredNotify;
use App\Notifications\Admin\TariffOverlappedNotify;
use App\Notifications\Admin\TariffOverviewConflictNotify;
use App\Notifications\Admin\TimeOverlappedNotify;
use Illuminate\Support\Facades\Log;

class TodoApp
{
    public static function create($type, $is_important = false, $data = [])
    {
        try {
            $inputs = [
                'todo' => 'Please check your lines in Tariffs Overview: you have same time frames in different Tariffs',
                'tags' => [['type' => 'danger', 'tag' => 'Tariff Overview Conflicts']],
                'type' => $type,
                'is_important' => $is_important,
                'data' => $data,
            ];
            $users = User::query();

            if ($type != 'time-overlapping' && $type != 'tariff-overlapping') {
                $users->whereHas('roles', function ($q) {
                    $q->where('name', 'Admin');
                });
            }
            $users = $users->get();
            if ($type == 'time-overlapping') { // red lines
                $inputs['todo'] = 'Please check your lines in Activity Overview: you have same time frames in different Lines';
                $inputs['tags'] = [['type' => 'danger', 'tag' => 'Time Overlapping']];

                foreach ($users as $user) {
                    $user->notify(new TimeOverlappedNotify($user, $inputs['todo'], $data['connection_report_id']));
                }

            } elseif ($type == 'tariff-overlapping') { // yellow lines
                $inputs['todo'] = 'Please check your lines in Activity Overview: you have same time frames in different Lines';
                $inputs['tags'] = [['type' => 'warning', 'tag' => 'Tariff Overlapping']];
                foreach ($users as $user) {
                    $user->notify(new TariffOverlappedNotify($user, $inputs['todo'], $data['connection_report_id']));
                }
            } elseif ($type == 'tariff-overview-conflicts') {  // red lines in global tariff overview
                $inputs['todo'] = 'Please check your lines in Tariffs Overview: you have same time frames in different Tariffs';
                $inputs['tags'] = [['type' => 'danger', 'tag' => 'Tariff Overview Conflicts']];
                foreach ($users as $user) {
                    $user->notify(new TariffOverviewConflictNotify($user, $inputs['todo'], $data['tariff_id']));
                }

            } elseif ($type == 'operation-time-exceeds') { // i.e. - more operation time incurred than planned operation time.
                if (isset($data['single_customer']) && $data['single_customer'] == true) {
                    $inputs['todo'] = __('locale.A customer has more operation time incurred than planned', ['customer_name' => $data['customer_name']]);
                    $inputs['tags'] = [['type' => 'primary', 'tag' => 'Operation time exceeds']];
                } else {
                    $inputs['todo'] = __('locale.Several customers have more operation time incurred than planned');
                    $inputs['tags'] = [['type' => 'primary', 'tag' => 'Operation time exceeds']];
                }
                foreach ($users as $user) {
                    $user->notify(new CustomerOperationTimeExceededNotify($user, $inputs['todo']));
                }
                $inputs['tags'] = [['type' => 'danger', 'tag' => 'Operation time exceeds']];
            } elseif ($type == 'recover-pending-connection') {
                $inputs['todo'] = 'You have pending connections to fill information';
                $inputs['tags'] = [['type' => 'info', 'tag' => 'Live track connection recovered']];
                foreach ($users as $user) {
                    $user->notify(new LivetrackConnectionsRecoveredNotify($user, $inputs['todo']));
                }
            }

            // store todo item for each user
            foreach ($users as $user) {
                $inputs['user_id'] = $user->id;
                if ($type == 'tariff-overlapping') {
                    $inputs['data'] = ['connection_report_id' => $data['connection_report_id']];
                    $todoExists = Todo::where('data->connection_report_id', '=', $data['connection_report_id'])
                        ->where('user_id', $user->id)
                        ->exists();
                    if (!$todoExists) {
                        Todo::create($inputs);
                    }
                } else {
                    Todo::create($inputs);
                }

            }
        } catch (\Throwable $t) {
//            dd($t->getMessage());
            Log::error(json_encode(['error' => 'Todo creation error', 'msg' => $t->getMessage()]));
        }
    }


    public static function removeTodo($type, $id)
    {
        try {
            if ($type == 'time-overlapping' || $type == 'tariff-overlapping') {
                Todo::where('type', $type)->where('data->connection_report_id', $id)->delete();
                Notification::whereIn('type', ['App\Notifications\Admin\TimeOverlappedNotify', 'App\Notifications\Admin\TariffOverlappedNotify'])
                    ->where('data->type_id', $id)
                    ->delete();
            } elseif ($type == 'tariff-overview-conflicts') {
                Todo::where('type', $type)->where('data->tariff_id', $id)->delete();
                Notification::whereIn('type', ['App\Notifications\Admin\TariffOverviewConflictNotify'])
                    ->where('data->type_id', $id)
                    ->delete();
            } elseif ($type == 'operation-time-exceeds') {
                Todo::where('type', $type)->where('data->tariff_id', $id)->delete();
                Notification::whereIn('type', ['App\Notifications\Admin\CustomerOperationTimeExceededNotify'])
                    ->where('data->type_id', $id)
                    ->delete();
            }

        } catch (\Throwable $t) {
//            dd($t->getMessage());
            Log::error(json_encode(['error' => 'Todo remove error', 'msg' => $t->getMessage()]));
        }
    }
}
