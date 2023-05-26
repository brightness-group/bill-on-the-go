<?php

namespace App\Http\Middleware;

use App\Models\Tenant\Livetrack;
use App\Services\ConnectionRecoveryService;
use Closure;
use Illuminate\Http\Request;
use Tenancy\Facades\Tenancy;

class LoggedOutRegisterTimer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $isLoggedIn = $request->path() == 'logout';

        if ($isLoggedIn && Tenancy::getTenant()) {
            $liveTrack = Livetrack::where('user_id', auth()->id())->first();

            if (!empty($liveTrack)) {
                $counterStart   = $liveTrack->start_date;
                $counterEnd     = now()->setTimezone(config('site.default_timezone'));
                $groupId        = $liveTrack->bdgo_id;

                auth()->user()->connection_recovery()->create([
                    'bdgo_gid' => $groupId,
                    'start_date' => $counterStart,
                    'end_date' => $counterEnd,
                    'livetrack_id' => $liveTrack->id
                ]);

                // Remove from livetracks records
                $connectionRecoveryService = new ConnectionRecoveryService();
                $connectionRecoveryService->removeLivetracksOnStopChronosByUserId(auth()->id());

                unset($_COOKIE['storedTimerBR']);
                setcookie('storedTimerBR', null, -1, '/');
            }
        }

        return $next($request);
    }
}
