<?php

namespace App\Helpers;

use Carbon\Carbon;

class AOHelpers
{
    /**
     * Get calculation for interval with price individual.
     *
     * @param object $connection
     * @param object|null $tariff
     * @return array
     */
    public static function calculateIntervalWithPriceIndividual(object $connection,object $tariff = null): array
    {
        if ($tariff) {
            $tariff_price = floatval($tariff->formatPriceForStored($tariff->price));
            $calculatedUnits = $connection->calculateUnit();
            $withoutInterval = $connection->duration();
            $withInterval = $calculatedUnits * $tariff->interval;
            $intervalTime = ($withInterval - $withoutInterval);
            $withoutIntervalPrice = ((floor($withoutInterval / $tariff->interval)) * $tariff_price);
            $withIntervalPrice = (($calculatedUnits * $tariff_price));
            $plannedOperatingTime = Helper::convertToMinutes($connection->customer->planned_operating_time);

            return [
                'without_interval' => $withoutInterval,
                'with_interval' => $withInterval,
                'interval_time' => $intervalTime,
                'without_interval_price' => $withoutIntervalPrice,
                'with_interval_price' => $withIntervalPrice,
                'interval_price' => ($withIntervalPrice - $withoutIntervalPrice),
                'planned_operating_time' => $plannedOperatingTime
            ];
        }
        return [
            'without_interval' => 0,
            'with_interval' => 0,
            'interval_time' => 0,
            'without_interval_price' => 0,
            'with_interval_price' => 0,
            'interval_price' => 0,
            'planned_operating_time' => 0
        ];
    }

    /**
     * Get calculation for units.
     *
     * @param object $connection
     * @param object|null $tariff
     * @return int|float|null
     */
    public static function calculateUnit(object $connection, object $tariff = null)
    {
        if (!is_null($tariff)) {
            $interval = (int)$tariff->interval;
            $value = $connection->bc_div_custom($connection->duration(), $interval, 1);

            if ((float)$value <= 1)
                return 1;
            else {
                if ((float)$value - floor((float)$value) == 0.0) {
                    return (float)$value;
                }
                else  {
                    $floor = (float)$value - floor((float)$value);
                    return (float)$value + (1- $floor);
                }
            }
        } else
            return null;
    }

    /**
     * Calculate duration from start & end date.
     * This method calculate minutes with ceil
     * Because Teamviewer didn't consider seconds and show ceil minutes.
     * 
     * @param Carbon/Carbon $startDate
     * @param Carbon/Carbon $endTime
     * 
     * @return Integer
     * 
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     */
    public static function calculateDuration(Carbon $startDate, Carbon $endTime)
    {
        return (int)ceil($endTime->floatDiffInMinutes($startDate));
    }
}
