<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Admin\RemindAdminPasswordChangeNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemindAdminPasswordChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $users = User::role('Admin')
                ->where('password_update_remind_at', '<=', now()->subMonths(6)->format('Y-m-d 23:59:59'))
                ->where('password_updated_at', '<=', now()->subMonths(6)->format('Y-m-d 23:59:59'))
                ->orWhereNull('password_update_remind_at')
                ->get();
            foreach ($users as $user) {
                $user->notify(new RemindAdminPasswordChangeNotify($user));
                $user->update(['password_update_remind_at' => now()]);
            }
        } catch (\Throwable $t) {
            Log::error(json_encode(['error' => 'Remind admin password email send ', 'msg' => $t->getMessage()]));
        }
    }
}
