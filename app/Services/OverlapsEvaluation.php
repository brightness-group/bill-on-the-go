<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\User;
//use App\Services\TodoApp as TodoAppService;
use Tenancy\Facades\Tenancy;

class OverlapsEvaluation
{

    protected static ?ConnectionReport $connectionReport;
    protected static $collection;

    public function __construct($connectionReport = null)
    {
        self::$connectionReport = $connectionReport;
    }

    public static function overlaps_check()
    {
        $overlapped_user_dates = [];
        $connectionReport = self::$connectionReport;
        ConnectionReport::query()->where('bdgogid',self::$connectionReport->bdgogid)
                                 ->where('billing_state','Bill')
                                 ->whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])
                                 ->whereNotIn('id',[self::$connectionReport->id])
                                 ->chunk(100, function ($connections) use ($connectionReport,$overlapped_user_dates) {
            foreach ($connections as $connection) {
                if ($connectionReport->start_date <= $connection->end_date && $connectionReport->end_date >= $connection->start_date) {
                    if ($connectionReport->bdgogid == $connection->bdgogid && ($connection->billing_state == 'Bill')) {
                        if ($connectionReport->userid == $connection->userid) {
                            $connectionReport->overlaps_user = true;
                            $connectionReport->save();
                            if (! $connection->overlaps_user) {
                                $connection->overlaps_user = true;
                                $connection->save();
                            }

                            // add red-line-item time overlapping resolve task item to todo list
                            if (empty($overlapped_user_dates[$connectionReport->bdgogid])
                                || (!empty($overlapped_user_dates[$connectionReport->bdgogid])
                                    && !in_array($connection->start_date->format('Y-m-d'), $overlapped_user_dates[$connectionReport->bdgogid]))) {
                                $overlapped_user_dates[$connectionReport->bdgogid] [] = $connection->start_date->format('Y-m-d');
                                // add 'red line time overlapping' to todo list
                                /*$data = [
                                    'connection_report_id' => self::$connectionReport->id
                                ];
                                TodoAppService::create('time-overlapping', true, $data);*/
                            }

                            if (!is_null($connection->overlaps_color)) {
                                $connectionReport->overlaps_color = $connection->overlaps_color;
                                $connectionReport->save();
                                return $connection;
                            } else {
                                $color = self::generateUniqueColorHls();
                                $connectionReport->overlaps_color = $color;
                                $connectionReport->save();
                                $connection->overlaps_color = $color;
                                $connection->save();
                                return $connection;
                            }
                        }
                    }
                }
            }
        });
    }

    public static function overlaps_unchecks()
    {
        self::$collection = ConnectionReport::where('overlaps_color',self::$connectionReport->overlaps_color)
                                            ->whereNotIn('id',[self::$connectionReport->id])
                                            ->whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])
                                            ->get();
        if (self::$connectionReport->overlaps_user) {
            self::$connectionReport->overlaps_color = null;
            self::$connectionReport->overlaps_user = false;
            self::$connectionReport->save();
            if (count(self::$collection) == 1) {
                $connection = self::$collection->filter(function ($connection) {
                    $connection->overlaps_color = null;
                    $connection->overlaps_user = false;
                    $connection->save();
                    return $connection;
                });
            }
        }
        return null;
    }

    public static function rebuildOverlappedConnections($companyId)
    {
        Tenancy::setTenant(Company::findOrFail($companyId));

        ConnectionReport::whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])
                        ->withTrashed()
                        ->chunk(100, function ($connections) {
                            foreach ($connections as $connection) {
                                if ($connection->overlaps_user) {
                                    $connection->overlaps_user = false;
                                    $connection->overlaps_color = null;
                                    $connection->save();
                                }
                            }
                        });

        $overlapped_user_dates = [];

        ConnectionReport::whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])
                        ->withTrashed()
                        ->chunk(100, function ($connections) use (&$companyId, $overlapped_user_dates) {
                            foreach ($connections as $connection) {
                                ConnectionReport::withTrashed()->whereNotIn('id', [$connection->id])->chunk(100, function ($connections) use (&$connection, &$companyId, $overlapped_user_dates) {
                                    foreach ($connections as $item) {
                                        if ($connection->start_date <= $item->end_date && $connection->end_date >= $item->start_date) {
                                            if ($connection->bdgogid == $item->bdgogid && $item->billing_state == 'Bill') {
                                                if ($connection->userid == $item->userid) {
                                                    $connection->overlaps_user = true;
                                                    $connection->save();
                                                    if (! $item->overlaps_user) {
                                                        $item->overlaps_user = true;
                                                        $item->save();
                                                    }

                                                    // add red-line-item time overlapping resolve task item to todo list
                                                    if (empty($overlapped_user_dates[$connection->userid])
                                                        || (!empty($overlapped_user_dates[$connection->userid])
                                                            && !in_array($connection->start_date->format('Y-m-d'), $overlapped_user_dates[$connection->userid]))) {
                                                        $overlapped_user_dates[$connection->userid] [] = $connection->start_date->format('Y-m-d');
                                                        // add 'red line overlapping' to todo list
                                                        /*$data = [
                                                            'connection_report_id' => self::$connectionReport->id
                                                        ];
                                                        TodoAppService::create('time-overlapping', true, $data);*/
                                                    }

                                                    if (!is_null($item->overlaps_color)) {
                                                        $connection->overlaps_color = $item->overlaps_color;
                                                        $connection->save();
                                                    } else {
                                                        $color = self::generateUniqueColorHls($companyId);
                                                        $connection->overlaps_color = $color;
                                                        $connection->save();
                                                        $item->overlaps_color = $color;
                                                        $item->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        });
    }

    private static function generateUniqueColorHls($company = null): string
    {
        $color = 'hsl(' . rand(200, 360) . ', ' . rand(0, 100) . '%, ' . rand(0, 50) . '%);';
        if ($company) {
            Tenancy::setTenant(Company::findOrFail($company));
            if (! ConnectionReport::whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])->withTrashed()->where('overlaps_user', true)->where('overlaps_color',$color)->exists()) {
                return $color;
            } else {
                self::generateUniqueColorHls($company);
            }
        } else {
            if (! ConnectionReport::whereBetween('created_at', [now()->subMonths(4)->startOfMonth()->toDateString(), now()->toDateString()])->withTrashed()->where('overlaps_user', true)->where('overlaps_color',$color)->exists()) {
                return $color;
            } else {
                self::generateUniqueColorHls(null);
            }
        }
    }

}
