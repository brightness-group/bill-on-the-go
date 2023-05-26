<?php

namespace App\Listeners;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\SharedUser;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;

class LoginUserEventInfo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!is_null($event->user)) {
            if (!empty($event->user->locale)) {
                session()->put('locale',$event->user->locale);
            }
        }
        $tenant = Tenancy::getTenant();
        if ($tenant) {
            $event->user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => request()->getClientIp()
            ]);
            if ($event->user->connection_recovery()->count()) {
                session(['connection_recovery' => true]);
            }
        }
    }
}
