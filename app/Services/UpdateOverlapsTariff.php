<?php

namespace App\Services;

use App\Models\Tenant\ConnectionReport;

class UpdateOverlapsTariff
{
    public function checkAndUpdateBorderLineEmergence()
    {
        $connections = ConnectionReport::whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])->withTrashed()->get();
        foreach ($connections as $connection) {
            try {
                $borderLineConnectionWatcher = new BorderLineConnectionWatcher($connection);
                $borderLineConnectionWatcher = $borderLineConnectionWatcher::borderlineEmergence();
            } catch (\Throwable $t) {
            }
        }
    }
}
