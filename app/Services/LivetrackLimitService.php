<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Tenant\ConnectionRecovery;
use App\Models\Tenant\Livetrack;
use Carbon\Carbon;

class LivetrackLimitService
{
    // Set maximum limit in minutes.
    // 1440 means 24 hours.
    private $maxLimit = 1440;

    public function __construct()
    {}

    /**
     * Check livetrack limit.
     *
     * @return bool
     */
    public function limitLivetrack()
    {
        $openLivetracks = Livetrack::where('end_date', null)->get();

        if (!empty($openLivetracks) && !$openLivetracks->isEmpty()) {
            $connectionRecoveries = [];

            $connectionRecoveryService = new ConnectionRecoveryService();

            foreach ($openLivetracks as $openLivetrack) {
                $now       = now()->setTimezone(config('site.default_timezone'));
                $startDate = Carbon::createFromFormat(
                                'Y-m-d H:i:s',
                                $openLivetrack->start_date,
                                config('site.default_timezone')
                            );

                if ($now->diffInMinutes($startDate) >= $this->maxLimit) {
                    $connectionRecoveries[] = [
                        'user_id'       => $openLivetrack->user_id,
                        'bdgo_gid'      => $openLivetrack->bdgo_id,
                        'start_date'    => $openLivetrack->start_date,
                        'end_date'      => $now,
                        'created_at'    => $now,
                        'livetrack_id'  => $openLivetrack->id
                    ];

                    // Remove from livetracks records
                    $connectionRecoveryService->removeLivetracksOnStopChronosByUserId($openLivetrack->user_id);

                    // Logout user and force to login again for ask resume timer.
                    Session::where('user_id', $openLivetrack->user_id)->delete();
                }
            }

            ConnectionRecovery::insert($connectionRecoveries);
        }

        return true;
    }
}
