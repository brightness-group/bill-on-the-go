<?php

namespace App\Services;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Livetrack;
//use App\Services\TodoApp as TodoAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tenancy\Facades\Tenancy;

class ConnectionRecoveryService
{
    public function __construct()
    {

    }

    /**
     * Recover pending connections
     *
     * @return bool
     */
    public function recoverPendingConnections()
    {
        $livetrackConnections = Livetrack::where('end_date', null)->get();

        $livetrack_connections_ids = [];
        $connectionsInputs = [];
        foreach ($livetrackConnections as $key => $livetrackConnection) {
            $now = now()->setTimezone(config('site.default_timezone'));
            $last_poll_time = Carbon::createFromFormat('Y-m-d H:i:s', $livetrackConnection->last_poll_date, config('site.default_timezone'));
            $diffInMinutes = $now->diffInMinutes($last_poll_time);

            if ($diffInMinutes > 1) {
                $customer = $livetrackConnection->bdgo_id
                    ? Customer::where('bdgogid', $livetrackConnection->bdgo_id)->first()
                    : null;
                $connectionsInputs[$key] = [
                    'id' => $this->generateRandomOwnAppId(),
                    'userid' => $livetrackConnection->user_id,
                    'username' => $livetrackConnection->user_name,
                    'bdgogid' => $livetrackConnection->bdgo_id,
                    'groupname' => !empty($customer->customer_name) ? $customer->customer_name : null,
                    'start_date' => $livetrackConnection->start_date,
                    'end_date' => $last_poll_time->addSeconds(30),
                    'isTV' => false,
                    'created_at' => now(),
                ];
                $livetrack_connections_ids[] = $livetrackConnection->id;
            }
        }
        if (count($connectionsInputs) > 0) {
            try {
                DB::beginTransaction();
                ConnectionReport::insert($connectionsInputs);
                Livetrack::whereIn('id', $livetrack_connections_ids)->delete();
//                TodoAppService::create('recover-pending-connection', true);
                DB::commit();
            } catch (\Throwable $t) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    public function generateRandomOwnAppId(): string
    {
        return 'tenant' . Tenancy::getTenant()->getTenantKey() . '-' .
            strtolower($this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4)
                . '-' . $this->generateRandomString(12));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
    }

    public function removeLivetracksOnStopChronosByUserId($user_id)
    {
        $livetrack = Livetrack::where('user_id', $user_id)->where('end_date', null)->orderBy('created_at', 'desc')->first();
        if (!empty($livetrack->id)) {
            $livetrack->end_date = now()->setTimezone(config('site.default_timezone'));
            $livetrack->save();

            $livetrack->delete();
        }
    }
}
