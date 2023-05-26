<?php

namespace App\Services;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Tariff;
//use App\Services\TodoApp as TodoAppService;
use Composer\Util\Tar;
use Illuminate\Database\Eloquent\Collection;

class BorderLineConnectionWatcher
{

    protected static ConnectionReport $connectionReport;

    public function __construct(ConnectionReport $connectionReport)
    {
        self::$connectionReport = $connectionReport;
    }

    public static function borderlineEmergence(): array
    {
        $initTariff = null;
        $tariffs = new Collection();

        if (self::$connectionReport->is_tariff_overlap_confirmed) {
            return [];
        }

        if (self::$connectionReport->tariff()->exists()) {
            $initTariff = self::$connectionReport->tariff;
            $customTariffs = Customer::where('bdgogid',self::$connectionReport->bdgogid)
                                        ->with(['tariffs' => function($query) {
                                            $query->whereNotIn('tariffs.id',[self::$connectionReport->tariff_id]);
                                        }])
                                        ->get();
            if (!count($customTariffs->flatMap->tariffs)) {

                $tariffs = Tariff::
                                      where('global',true)
                                    ->whereNotIn('id',[$initTariff->id])
                                    ->where('start_period','<=',self::$connectionReport->start_date->setTimezone(config('site.default_timezone')))
                                    ->get();
            }
            else {
                $tariffs = Tariff::
                                      whereIn('id',$customTariffs->flatMap->tariffs->pluck('id'))
                                    ->orWhere('global',true)
                                    ->whereNotIn('id',[$initTariff->id])
                                    ->where('start_period','<=',self::$connectionReport->start_date->setTimezone(config('site.default_timezone')))
                                    ->get();
            }

            foreach ($tariffs as $tariff) {
                if (self::endDateAfterOtherStartPeriodTariff($tariff,$initTariff)) {
                    if (self::$connectionReport->border_limit_evaluated()->exists()) {
                        $data = self::$connectionReport->border_limit_evaluated;
                        foreach ($data as $item) {
                            if (($item->tariff_related == $initTariff->id && $item->tariff_overlaped == $tariff->id) ||
                                ($item->tariff_related == $tariff->id && $item->tariff_overlaped == $initTariff->id)) {
                                // update overlaps_tariff
                                self::$connectionReport->overlaps_tariff = false;
                                self::$connectionReport->save();
                                return [];
                            }
                        }
                    }

                    // add yellow-line-item overlapping resolve task item to todo list
                    // add 'yellow line overlapping' to todo list
                    /*$data = [
                        'connection_report_id' => self::$connectionReport->id
                    ];
                    TodoAppService::create('tariff-overlapping', true,$data);*/

                    // update overlaps_tariff
                    self::$connectionReport->overlaps_tariff = true;
                    self::$connectionReport->save();

                    return [
                        'initBorderLimitCross' => $initTariff,
                        'endBorderLimitCross' => $tariff
                    ];
                }
            }
        }
        return [];
    }

    protected static function endDateAfterOtherStartPeriodTariff(Tariff $tariff,Tariff $related_tariff): bool
    {

        $start_related_tariff = strtotime($related_tariff->initial_time);
        $end_related_tariff = strtotime($related_tariff->end_time);
        if(in_array(true, $tariff->selected_days)) {
            $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
            $match = self::checkIfTariffMatchConnectionDayWeek(self::$connectionReport,$filtered_days);
            if ($match) {
                $connStart = strtotime(self::$connectionReport->start_date->setTimezone(config('site.default_timezone'))->format('H:i'));
                $connEnd = strtotime(self::$connectionReport->end_date->setTimezone(config('site.default_timezone'))->format('H:i'));
                $tariffInit = strtotime(date('H:i',strtotime($tariff->initial_time)));
                $tariffEnd = strtotime(date('H:i', strtotime($tariff->end_time)));

                if ($tariffInit > $tariffEnd) {
                    if ($connStart >= $tariffInit && $connEnd >= $tariffInit) {
                        return false;
                    }
                    if ($start_related_tariff < $end_related_tariff) {
                        if ($connStart < $connEnd) {
                            if ($connStart < $tariffInit && $connEnd > $tariffInit) {
                                return true;
                            }
                        }
                    } elseif ($start_related_tariff > $end_related_tariff) {
                        if ($connStart < $tariffInit && $connEnd > $tariffInit) {
                            return true;
                        }
                    }
//                    if ($connStart >= $tariffInit) {
//                        $tariffEnd = strtotime('+1 day', strtotime(date('H:i'), strtotime($tariff->end_time)));
//                        if ($connEnd <= $tariffEnd) {
//                            return true;
//                        }
//                    } elseif ($connStart <= $tariffInit && $connStart <= $tariffEnd) {
//                            return true;
//                    }
                } elseif ($tariffInit < $tariffEnd) {
                    if ($start_related_tariff < $end_related_tariff) {
                        if ($connEnd > $end_related_tariff && $connEnd > $tariffInit) {
                            return true;
                        }
                    } elseif ($start_related_tariff > $end_related_tariff) {
                        if ($connStart > $connEnd) {
                            if ($connEnd > $tariffInit) {
                                return true;
                            }
                        } elseif ($connStart < $connEnd) {
                            if ($connStart < $tariffInit && $connEnd > $tariffInit) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Return the pair key-value array of the selected days
     * @param $selectedDaysArray
     * @return array
     */
    protected static function getTariffSelectedDays($selectedDaysArray): array
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
    protected static function checkIfTariffMatchConnectionDayWeek($connection, $array_days): bool
    {
        return key_exists(strtolower($connection->start_date->setTimezone(config('site.default_timezone'))->format('l')),$array_days) && key_exists(strtolower($connection->end_date->setTimezone(config('site.default_timezone'))->format('l')),$array_days);
    }

}
