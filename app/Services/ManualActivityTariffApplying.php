<?php

namespace App\Services;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Tariff;
use Illuminate\Database\Eloquent\Collection;

class ManualActivityTariffApplying
{

    protected static ConnectionReport $connection;
    private static bool $permanent = false;
    private static ?string $bdgogid = null;

    public function __construct(ConnectionReport $connection, array $attributes)
    {
        self::$connection = $connection;
        if (array_key_exists('permanent',$attributes)) {
            if ($attributes['permanent'])
                self::$permanent = true;
        }
        if (array_key_exists('bdgogid',$attributes)) {
            self::$bdgogid = $attributes['bdgogid'];
        }
    }

    public static function applyTariffToConnection()
    {
        $group = Customer::query()->where('bdgogid', self::$bdgogid)->first();
        $customs_tariffs = $group->tariffs()->get();
        $global_tariffs = Tariff::query()->where('overlap_status', false)->where('global', true)->get();
        $flag = false;

        if (count($customs_tariffs)) {
            foreach ($customs_tariffs as $tariff) {
                if (self::checkForTariffRelations($tariff, self::$connection)) {
                    self::$connection->tariff_id = $tariff->id;
                    self::$connection->save();

                    $price = self::$connection->calculatePrice();
                    self::$connection->update(['price' => $price]);
                    $flag = true;
                }
            }
        } elseif (count($global_tariffs) && !$flag) {
            foreach ($global_tariffs as $tariff) {
                if (self::checkForTariffRelations($tariff, self::$connection)) {
                    self::$connection->tariff_id = $tariff->id;
                    self::$connection->save();

                    $price = self::$connection->calculatePrice();
                    self::$connection->update(['price' => $price]);
                    $flag = true;
                }
            }
        }
        return $flag;
    }

    private static function convertDateTime($datetime)
    {
        return !$datetime instanceof \DateTime ? date_create_from_format('Y-m-d H:i:s',$datetime) : $datetime;
    }

    /**
     * Function for check if the tariff apply to any related connection
     *
     * @param $tariff
     * @param $connection
     * @return bool
     */
    public static function checkForTariffRelations($tariff, $connection): bool
    {
        if(in_array(true,$tariff->selected_days)) {
            $filtered_days = self::getTariffSelectedDays($tariff->selected_days);
            $match = self::checkIfTariffMatchConnectionDayWeek($connection,$filtered_days);
            if ($match) {
                $connStart = strtotime($connection->start_date->setTimezone(config('site.default_timezone'))->format('H:i:s'));
                $connEnd = strtotime($connection->end_date->setTimezone(config('site.default_timezone'))->format('H:i:s'));
                $tariffInit = strtotime($tariff->initial_time);
                $tariffEnd = strtotime($tariff->end_time);
                if ($tariffInit > $tariffEnd) {
                    if ($connStart >= $tariffInit) {
                        return true;
                    } elseif ($connStart <= $tariffInit) {
                        if ($connStart <= $tariffEnd) {
                            return true;
                        }
                    }
                } elseif ($tariffInit < $tariffEnd) {
                    if ($connStart >= $tariffInit) {
                        if ($connStart <= $tariffEnd && $connEnd >= $tariffInit) {
                            return true;
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
        return key_exists(strtolower($connection->start_date->format('l')),$array_days) && key_exists(strtolower($connection->end_date->format('l')),$array_days);
    }
}
