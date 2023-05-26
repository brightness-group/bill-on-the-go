<?php

namespace App\Services;

use App\Events\TeamviewerDataRetrievalProcessed;
use App\Jobs\StoreTeamviewerReportsJob;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Device;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\Tariff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


/**
 *
 */
class RetrieveDataFromAPI
{

    public static function ping($access_token): bool
    {
        try {
            $response = Http::retry(3, 600)->withToken($access_token)->get('https://webapi.anydesk.com/api/v1/ping');

            $response = $response->json();

            if (!$response['token_valid']) {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function groups($access_token): bool
    {
        $response = Http::withToken($access_token)->get('https://webapi.anydesk.com/api/v1/groups');

        $status = $response->status();
        $response = $response->json();

        if ($status !== 200) {
            return false;
        }
        else {
            $function_groups = $response['groups'];
            if (self::countGroupDB()) {
                foreach ($function_groups as $group) {
                    $aux = Customer::withTrashed()->where('bdgogid',$group['id'])->first();
                    if (self::groupExistDB($group['id'])) {
                        if ($aux && key_exists('owner',$group)) {
                            if(!$aux->shared_users()->where('shared_user_id',$group['owner']['userid'])->exists()) {
                                if (SharedUser::where('id',$group['owner']['userid'])->exists()) {
                                    $aux->shared_users()->attach($group['owner']['userid']);
                                } else {
                                    $aux->shared_users()->create([
                                        'id' => $group['owner']['userid'],
                                        'name' => $group['owner']['name']
                                    ]);
                                }
                            }
                        }
                        if ($aux && key_exists('shared_with',$group)) {
                            foreach ($group['shared_with'] as $user) {
                                if(!$aux->shared_users()->where('shared_user_id',$user['userid'])->exists()) {
                                    if (SharedUser::where('id',$user['userid'])->exists()) {
                                        $aux->shared_users()->attach($user['userid']);
                                    } else {
                                        $aux->shared_users()->create([
                                            'id' => $user['userid'],
                                            'name' => $user['name']
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        self::createGroupRetrieve($group);
                    }
                }
            } else {
                foreach ($function_groups as $group) {
                    self::createGroupRetrieve($group);
                }
            }
        }
        return true;
    }

    private static function countGroupDB()
    {
        return Customer::withTrashed()->count();
    }

    private static function createGroupRetrieve($group)
    {
        $created = Customer::create([
            'bdgogid' => $group['id'],
            'customer_name' => $group['name'],
            'customer_permissions' => $group['permissions']
        ]);

        if (key_exists('owner',$group)) {
            if (SharedUser::where('id',$group['owner']['userid'])->exists()) {
                $created->shared_users()->attach($group['owner']['userid']);
            } else {
                $created->shared_users()->create([
                    'id' => $group['owner']['userid'],
                    'name' => $group['owner']['name']
                ]);
            }
        }
        if(key_exists('shared_with',$group)) {
            foreach ($group['shared_with'] as $user) {
                if (SharedUser::where('id',$user['userid'])->exists()) {
                    $created->shared_users()->attach($user['userid']);
                } else {
                    $created->shared_users()->create([
                        'id' => $user['userid'],
                        'name' => $user['name']
                    ]);
                }
            }
        }
    }

    private static function groupExistDB($groupID)
    {
        return Customer::withTrashed()->where('bdgogid',$groupID)->exists();
    }

    public static function users($access_token)
    {
        $response = Http::withToken($access_token)->get('https://webapi.anydesk.com/api/v1/users',[
            'full_list' => 'true'
        ]);

        $status = $response->status();
        $response = $response->json();

        if ($status !== 200) {
            return false;
        }
        else {
            $function_devices = $response['devices'];


        }
    }  // in development ...

    public static function devices($access_token)
    {
        $response = Http::withToken($access_token)->get('https://webapi.anydesk.com/api/v1/devices');

        $status = $response->status();
        $response = $response->json();

        if ($status !== 200) {
            return false;
        }
        else {
            $function_devices = $response['devices'];

            foreach ($function_devices as $device) {
                if (!Device::where('id', $device['device_id'])->exists())
                    self::createDeviceRetrieved($device);
                else {
                    $item = Device::query()->where('id',$device['device_id'])->first();
                    if (key_exists('alias',$device)) {
                        if ($item->alias != $device['alias'])
                            $item->alias = $device['alias'];
                    }
                    if (key_exists('description',$device)) {
                        if ($item->description != $device['description'])
                            $item->description = $device['description'];
                    }
                    if (key_exists('bdgogid', $device)) {
                        if ($item->bdgogid != $device['bdgogid'])
                            $item->bdgogid = $device['bdgogid'];
                    }
                    if (key_exists('online_state', $device)) {
                        if ($item->online_state != $device['online_state'])
                            $item->online_state = $device['online_state'];
                    }
                    $item->save();
                }
            }
        }
        return true;
    }

    private static function countDeviceDB()
    {
        return Device::all()->count();
    }

    private static function createDeviceRetrieved($device)
    {
        $object = new Device();
        $object->id = $device['device_id'];
        $object->alias = $device['alias'];
        if (key_exists('description',$device))
            $object->description = $device['description'];
        if (key_exists('bdgogid',$device))
            $object->bdgogid = $device['bdgogid'];
        if (key_exists('online_state',$device))
            if ($device['online_state'] == 'Offline')
                $object->online_state = $device['online_state'];

        $object->save();
    }

    public static function connections($access_token, $user = null, $offset_id = null)
    {
        return self::processAllConnections($access_token, $user);
    }

    public static function saveConnectionsAPI($tvConnections, $isLast = false, $userEmail = null, $company = null)
    {
        try {
            foreach ($tvConnections as $tvConnection) {
                if ((key_exists('start_date', $tvConnection) && $tvConnection['start_date'] != null)
                    && (key_exists('end_date', $tvConnection) && $tvConnection['end_date'] != null)) {
                    if (key_exists('bdgogid', $tvConnection)) {
                        self::createConnectionRetrieve($tvConnection);
                    }
                }
            }

            // notify user via email that Teamviewer data processed.
            if ($isLast) {
                Log::info(json_encode(['method' => 'saveConnectionsAPI@success', 'action' => 'notified to user via email that @all Teamviewer data processedzz.']));
                TeamviewerDataRetrievalProcessed::dispatch($userEmail, $company);
                DB::table('companies')->where('id', $company->id)->update(['tv_sync_in_progress' => false]);
            }

            return true;
        } catch (\Throwable $t) {
            Log::error(json_encode(['method' => 'RetrieveDataFromAPI@saveConnectionsAPI', 'Error Message' => $t->getMessage(), 'details' => $t->getTraceAsString(), 'company' => !empty($company->name) ? $company->name : 'N/A']));
            return false;
        }
    }

    public static function processAllConnections($access_token, $user = null, $offset_id = null)
    {
        try {
            $endDate = now();
            $params = ['has_code' => 'false'];

            $company = DB::table('companies')->where('anydesk_access_token', $access_token)->first();
            if (!empty($company->sync_cron_date)) {
                $lastTVDataFetchDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $company->sync_cron_date)->subDay();
                $params['from_date'] = $lastTVDataFetchDate->format("Y-m-d"); // add start date filter param
                $params['to_date'] = now()->format("Y-m-d"); // add end date filter param
            } else {
                // Add temp solution for sync 63 days only data.
                $params['from_date'] = now()->subDays(63)->format("Y-m-d");
                $params['to_date'] = now()->format("Y-m-d");
            }

            if ($offset_id) {
                $params['offset_id'] = $offset_id;
            }
            $response = Http::withToken($access_token)->get('https://webapi.anydesk.com/api/v1/reports/connections', $params);

            if ($response->status() !== 200) {
                if ($company->tv_sync_in_progress) {
                    DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                        'tv_sync_in_progress' => false
                    ]);
                }
                return false;
            } else {
                // 1) process current data
                $tvConnections = $response->json()['records'];
                if (!$company->tv_sync_in_progress) {
                    DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                        'tv_sync_in_progress' => true
                    ]);
                }

                if(count($tvConnections) > 0 ){
                    // 1) dispatch job to save retrieved via chunks
                    if ($response->json()['records_remaining'] <= 0 && !empty($user->email)) {
                        self::dispatchJobsByChunks($tvConnections, true, $company, $user->email);
                    } else {
                        self::dispatchJobsByChunks($tvConnections,false, $company, null);
                    }
                    // 2) fetch & store remaining data recursively - i.e. handle pagination request
                    if ($response->json()['records_remaining'] > 0 && $offset_id = $response->json()['next_offset']) {
                        self::processAllConnections($access_token, $user, $offset_id);
                    }
                } else {
                    if ($company->tv_sync_in_progress) {
                        DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                            'tv_sync_in_progress' => false
                        ]);
                    }
                }
                // track last TV data fetch time
                DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                    'sync_cron_date' => $endDate
                ]);

                return true;
            }
        } catch (\Throwable $t) {
            $company = DB::table('companies')->where('anydesk_access_token', $access_token)->first();
            Log::error(json_encode(['method' => 'RetrieveDataFromAPI@processAllConnections', 'Error Message' => $t->getMessage(), 'Line number' => $t->getLine(), 'company' => !empty($company->name) ? $company->name : 'N/A', 'details' => $t->getTraceAsString()]));
            if ($company->tv_sync_in_progress) {
                DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                    'tv_sync_in_progress' => false
                ]);
            }
            return false;
        }
    }

    private static function createConnectionRetrieve($connection)
    {
        $user = SharedUser::where('id',$connection['userid'])->first();
        if (!$user->connections()->withTrashed()->where('id',$connection['id'])->exists()) {
            $connection['start_date'] = date_create($connection['start_date']);
            $connection['end_date'] = date_create($connection['end_date']);

            if (key_exists('bdgogid',$connection)) {
                $connection['tariff_id'] = self::checkForTariffRelations($connection);
                $timezone = new \DateTimeZone('UTC');
                $connection['start_date'] = date_timezone_set($connection['start_date'],$timezone);
                $connection['end_date'] = date_timezone_set($connection['end_date'],$timezone);
            }
            if (key_exists('devicename',$connection)) {
                $device = Device::query()->where('alias',$connection['devicename'])->first();
                if ($device) {
                    $connection['device_id'] = $device->id;
                }
                unset($connection['deviceid']);
            }

            // code for the overlaps connections mark color
            // check without printed and hidden status for last 4 months for overlapping: tariff,connection,user.
            $overlapped_user_dates = [];
            $lastFourMonths = now()->firstOfMonth()->subMonths(4)->firstOfMonth();
            ConnectionReport::where('created_at', '>=', $lastFourMonths)
                ->where('printed', false)
                ->chunk(100, function ($items) use (&$connection) {
                    foreach ($items as $item) {
                        if ($connection['start_date'] <= $item->end_date && $connection['end_date'] >= $item->start_date) {
                            if (key_exists('bdgogid', $connection) && !is_null($item->bdgogid)) {
                            if ($connection['bdgogid'] == $item->bdgogid && ($connection['billing_state'] == 'Bill' && $item->billing_state == 'Bill')) {
                                if ($connection['userid'] == $item->userid) { // removed device check condition.
                                    $connection['overlaps_user'] = true;
                                    if (!$item->overlaps_user) {
                                        $item->overlaps_user = true;
                                        $item->save();
                                    }

                                    // add red-line-item time overlapping resolve task item to todo list
                                    /*if (empty($overlapped_user_dates[$item->userid])
                                        || (!empty($overlapped_user_dates[$item->userid])
                                            && !in_array(Carbon::parse($connection['start_date'])->format('Y-m-d'), $overlapped_user_dates[$item->userid]))) {
                                        $overlapped_user_dates[$item->userid] [] = Carbon::parse($connection['start_date'])->format('Y-m-d');

                                        $data = [
                                            'connection_report_id' => $connection['id']
                                        ];
                                        // add 'red line overlapping' to todo list
                                        // TodoAppService::create('time-overlapping', true, $data);
                                    }*/

                                    if (!is_null($item->overlaps_color)) {
                                        $connection['overlaps_color'] = $item->overlaps_color;
                                        break;
                                    } else {
                                        $color = self::generateUniqueColorHls();
                                        $connection['overlaps_color'] = $color;
                                        $item->overlaps_color = $color;
                                        $item->save();
                                        break;
                                    }
                                }
                            }
                        }
                        }
                    }
                });

            $object = new ConnectionReport();
            $object->fill($connection);
            $calc = $user->connections()->save($object);
            if ($calc) {
                // check overlaps tariff & update connection accordingly.
                $calc->borderLineEmergency();

                if ($calc->tariff) {
                    $price = $calc->calculatePrice();
                    $calc->update(['price' => $price]);
                }
            }
        }
    }

    private static function generateUniqueColorHls(): string
    {
        $color = 'hsl(' . rand(200, 360) . ', ' . rand(0, 100) . '%, ' . rand(0, 50) . '%);';
        if (! ConnectionReport::withTrashed()->whereNotNull('overlaps_color')->where('overlaps_color',$color)->exists()) {
            return $color;
        } else {
            self::generateUniqueColorHls();
        }
    }

    /**
     * If the connection have relation with one of the
     * custom or global tariffs if exists
     * @param $connection
     * @return ?int
     */
    private static function checkForTariffRelations($connection): ?int
    {
        $conn = $connection;
        $timezone = new \DateTimeZone(config('site.default_timezone'));
        $conn['start_date'] = date_timezone_set($conn['start_date'],$timezone);
        $conn['end_date'] = date_timezone_set($conn['end_date'],$timezone);

        $group = Customer::where('bdgogid',$connection['bdgogid'])->first();
        $customTariffs = Collection::empty();
        if ($group) {
            $customTariffs = $group->tariffs()->get();
        }

        $globalTariffs = Tariff::where('global',true)->get();

        if (count($customTariffs)) {
            foreach ($customTariffs as $tariff) {
                if (self::evaluateCustomTariffByGroup($conn,$tariff)) {
                    if ($tariff->permanent) {
                        if ($conn['start_date'] >= self::convertDateTime($tariff->start_period)) {
                            if(in_array(true,$tariff->selected_days)) {
                                $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
                                $match = self::checkIfTariffMatchConnectionDayWeek($conn,$filtered_days);
                                if ($match) {
                                    $connStart = strtotime($conn['start_date']->format('H:i'));
                                    $connEnd = strtotime($conn['end_date']->format('H:i'));
                                    $tariffInit = strtotime($tariff->initial_time);
                                    $tariffEnd = strtotime($tariff->end_time);
                                    if ($tariffInit > $tariffEnd) {
                                        if ($connStart >= $tariffInit) {
                                            return $tariff->id;
                                            break;
                                        } elseif ($connStart <= $tariffInit) {
                                            if ($connStart <= $tariffEnd) {
                                                return $tariff->id;
                                                break;
                                            }
                                        }
                                    } elseif ($tariffInit < $tariffEnd) {
                                        if ($connStart >= $tariffInit) {
                                            if ($connStart <= $tariffEnd && $connEnd >= $tariffInit) {
                                                return $tariff->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($tariff->start_period && $tariff->end_period) {
                        if ($conn['start_date'] >= self::convertDateTime($tariff->start_period) &&
                            $conn['end_date'] <= self::convertDateTime($tariff->end_period)) {
                            if(in_array(true,$tariff->selected_days)) {
                                $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
                                $match = self::checkIfTariffMatchConnectionDayWeek($conn,$filtered_days);
                                if ($match) {
                                    $connStart = strtotime($conn['start_date']->format('H:i'));
                                    $connEnd = strtotime($conn['end_date']->format('H:i'));
                                    $tariffInit = strtotime($tariff->initial_time);
                                    $tariffEnd = strtotime($tariff->end_time);
                                    if ($tariffInit > $tariffEnd) {
                                        if ($connStart >= $tariffInit) {
                                            return $tariff->id;
                                            break;
                                        } elseif ($connStart <= $tariffInit) {
                                            if ($connStart <= $tariffEnd) {
                                                return $tariff->id;
                                                break;
                                            }
                                        }
                                    } elseif ($tariffInit < $tariffEnd) {
                                        if ($connStart >= $tariffInit) {
                                            if ($connStart <= $tariffEnd && $connEnd >= $tariffInit) {
                                                return $tariff->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!is_null($globalTariffs)) {
            foreach ($globalTariffs as $tariff) {
                if ($tariff->permanent) {
                    if ($conn['start_date'] >= self::convertDateTime($tariff->start_period)) {
                        if(in_array(true,$tariff->selected_days)) {
                            $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
                            $match = self::checkIfTariffMatchConnectionDayWeek($conn,$filtered_days);
                            if ($match) {
                                $connStart = strtotime($conn['start_date']->format('H:i'));
                                $connEnd = strtotime($conn['end_date']->format('H:i'));
                                $tariffInit = strtotime($tariff->initial_time);
                                $tariffEnd = strtotime($tariff->end_time);
                                if ($tariffInit > $tariffEnd) {
                                    if ($connStart >= $tariffInit) {
                                        return $tariff->id;
                                        break;
                                    } elseif ($connStart <= $tariffInit) {
                                        if ($connStart <= $tariffEnd) {
                                            return $tariff->id;
                                            break;
                                        }
                                    }
                                } elseif ($tariffInit < $tariffEnd) {
                                    if ($connStart >= $tariffInit) {
                                        if ($connStart <= $tariffEnd && $connEnd >= $tariffInit) {
                                            return $tariff->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif ($tariff->start_period && $tariff->end_period) {
                    if ($conn['start_date'] >= self::convertDateTime($tariff->start_period) &&
                        $conn['end_date'] <= self::convertDateTime($tariff->end_period)) {
                        if(in_array(true,$tariff->selected_days)) {
                            $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
                            $match = self::checkIfTariffMatchConnectionDayWeek($conn,$filtered_days);
                            if ($match) {
                                $connStart = strtotime($conn['start_date']->format('H:i'));
                                $connEnd = strtotime($conn['end_date']->format('H:i'));
                                $tariffInit = strtotime($tariff->initial_time);
                                $tariffEnd = strtotime($tariff->end_time);
                                if ($tariffInit > $tariffEnd) {
                                    if ($connStart >= $tariffInit) {
                                        return $tariff->id;
                                        break;
                                    } elseif ($connStart <= $tariffInit) {
                                        if ($connStart <= $tariffEnd) {
                                            return $tariff->id;
                                            break;
                                        }
                                    }
                                } elseif ($tariffInit < $tariffEnd) {
                                    if ($connStart >= $tariffInit) {
                                        if ($connStart <= $tariffEnd && $connEnd >= $tariffInit) {
                                            return $tariff->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    private static function convertDateTime($datetime)
    {
        return !$datetime instanceof \DateTime ? date_create_from_format('Y-m-d H:i:s',$datetime) : $datetime;
    }

    /**
     * Check if the custom tariff belong to the group of the connection
     *
     * @param $connection
     * @param $tariff
     * @return bool
     */
    private static function evaluateCustomTariffByGroup($connection, $tariff): bool
    {
        if (key_exists('bdgogid',$connection)) {
            return !is_null($tariff->customers()->where('bdgogid',$connection['bdgogid'])->first());
        } else return false;
    }

    /**
     * Return the pair key-value array of the selected days
     * @param $selectedDaysArray
     * @return array
     */
    private static function getTariffSelectedDays($selectedDaysArray): array
    {
        return array_filter($selectedDaysArray,function ($value) {
            if ($value == true)
                return $value;
        });
    }

    /**
     * Get the day of the week of the connection start and end date
     * and check if matches with one of the tariff selected days
     * @param $connection
     * @param $array_days
     * @return bool
     */
    private static function checkIfTariffMatchConnectionDayWeek($connection,$array_days): bool
    {
        return key_exists(strtolower($connection['start_date']->format('l')),$array_days) && key_exists(strtolower($connection['end_date']->format('l')),$array_days);
    }

    public function dispatchJobsByChunks($tvConnections, $isLastPageRecords, $company, $userEmail = null,)
    {
        $iterations = ceil(count($tvConnections) >= 100 ? count($tvConnections) / 100 : 1);
        $counter = 1;
        foreach (collect($tvConnections)->chunk(100) as $chunkedRecords) {
            if ($isLastPageRecords && $counter === $iterations) { // last chunk
                StoreTeamviewerReportsJob::dispatch($chunkedRecords, $isLastPageRecords, $userEmail, $company);
            } else {
                StoreTeamviewerReportsJob::dispatch($chunkedRecords, false, $userEmail, $company);
            }
            $counter++;
        }
    }

    public static function getConnectionsByPagination($access_token, $user = null, $offset_id = null)
    {
        try {
            $params = ['has_code' => 'false'];
            $company = DB::table('companies')->where('anydesk_access_token', $access_token)->first();
            if (!empty($company->sync_cron_date)) {
                $lastTVDataFetchDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $company->sync_cron_date)->subDay();
                $params['from_date'] = $lastTVDataFetchDate->format("Y-m-d"); // add start date filter param
                $params['to_date'] = now()->format("Y-m-d"); // add end date filter param
            } else {
                // Add temp solution for sync 63 days only data.
                $params['from_date'] = now()->subDays(63)->format("Y-m-d");
                $params['to_date'] = now()->format("Y-m-d");
            }

            if ($offset_id) {
                $params['offset_id'] = $offset_id;
            }
            $response = Http::retry(3,100)->withToken($access_token)->timeout(240)->get('https://webapi.anydesk.com/api/v1/reports/connections', $params);

            if ($response->status() !== 200) {
                return [
                    'status' => $response->status(),
                    'message' => 'Error occurred from TV api call',
                ];
            } else {
                $tvConnections = $response->json()['records'];
                if (count($tvConnections) > 0) {
                    return [
                        'status' => $response->status(),
                        'message' => 'success',
                        'tvConnections' => $tvConnections,
                        'email' => $user->email,
                        'next_offset' => $response->json()['records_remaining'] > 0 ? $response->json()['next_offset'] : null,
                        'remaining_records' => $response->json()['records_remaining'],
                    ];
                } else {
                    return [
                        'status' => $response->status(),
                        'message' => 'success',
                        'tvConnections' => [],
                        'email' => $user->email,
                        'next_offset' => null,
                        'remaining_records' => 0,
                    ];
                }
            }
        } catch (\Throwable $t) {
            $company = DB::table('companies')->where('anydesk_access_token', $access_token)->first();
            Log::error(json_encode(['method' => 'RetrieveDataFromAPI@processAllConnections', 'Error Message' => $t->getMessage(), 'Line number' => $t->getLine(), 'company' => !empty($company->name) ? $company->name : 'N/A', 'details' => $t->getTraceAsString()]));
            if ($company->tv_sync_in_progress) {
                DB::table('companies')->where('anydesk_access_token', $access_token)->update([
                    'tv_sync_in_progress' => false
                ]);
            }
            return [
                'status' => 500,
                'exception_code' => $t->getCode(),
                'line' => $t->getLine(),
                'message' => $t->getMessage(),
            ];
        }
    }

}
