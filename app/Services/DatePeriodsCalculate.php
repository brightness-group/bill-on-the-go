<?php

namespace App\Services;

class DatePeriodsCalculate
{

    public function __construct()
    {
    }

    public static function evalPeriodRangeForCalendar($start_date, $end_date): int
    {
        if (self::evalPeriodRangeForCurrentMonth($start_date,$end_date))
            return 1;
        elseif (self::evalPeriodRangeForLastMonth($start_date, $end_date))
            return 2;
        elseif (self::evalPeriodRangeByThisQuarter($start_date, $end_date))
            return 3;
        elseif (self::evalPeriodRangeByLastQuarter($start_date, $end_date))
            return 4;
        elseif (self::evalPeriodRangeForCurrentYear($start_date, $end_date))
            return 5;
        elseif (self::evalPeriodRangeForLastYear($start_date, $end_date))
            return 6;
        else return 0;
    }

    public static function evalPeriodRangeForCurrentMonth($start_date, $end_date): int
    {
        $array = self::getCurrentMonthDates();
        $currentStart = $array['start_date'];
        $currentEnd = $array['end_date'];

        if ($start_date >= $currentStart && $end_date <= $currentEnd) {
            return 1;
        }
        return 0;
    }

    public static function evalPeriodRangeForLastMonth($start_date, $end_date): int
    {
        $array = self::getLastMonthDates();
        $lastStart = $array['start_date'];
        $lastEnd = $array['end_date'];

        if ($start_date >= $lastStart && $end_date <= $lastEnd) {
            return 1;
        }
        return 0;
    }

    public static function evalPeriodRangeByThisQuarter($start_date, $end_date): int
    {
        $array = self::getMonthsByQuarter('this');
        $thisQuarterStart = $array['start_date'];
        $thisQuarterEnd = $array['end_date'];

        if ($start_date >= $thisQuarterStart && $end_date <= $thisQuarterEnd) {
            return 1;
        }
        return 0;
    }

    public static function evalPeriodRangeByLastQuarter($start_date, $end_date): int
    {
        $array = self::getMonthsByQuarter('last');
        $lastQuarterStart = $array['start_date'];
        $lastQuarterEnd = $array['end_date'];

        if ($start_date >= $lastQuarterStart && $end_date <= $lastQuarterEnd) {
            return 1;
        }
        return 0;
    }

    public static function evalPeriodRangeForCurrentYear($start_date, $end_date): int
    {
        $array = self::getCurrentYearDates();
        $currentYearStart = $array['start_date'];
        $currentYearEnd = $array['end_date'];

        if ($start_date >= $currentYearStart && $end_date <= $currentYearEnd) {
            return 1;
        }
        return 0;
    }

    public static function evalPeriodRangeForLastYear($start_date, $end_date): int
    {
        $array = self::getLastYearDates();
        $lastYearStart = $array['start_date'];
        $lastYearEnd = $array['end_date'];

        if ($start_date >= $lastYearStart && $end_date <= $lastYearEnd) {
            return 1;
        }
        return 0;
    }


    // RETURN DATE FORMAT

    public static function getCurrentMonthDates(): array
    {
        $firsDay = new \DateTime('first day of this month');
        $firsDay->setTime(0,0,0);
        $lastDay = new \DateTime('last day of this month');
        $lastDay->setTime(23,59,59);

        return [
            'start_date' => $firsDay,
            'end_date' => $lastDay
        ];
    }

    public static function getLastMonthDates(): array
    {
        $firsDay = new \DateTime('first day of last month');
        $firsDay->setTime(0,0,0);
        $lastDay = new \DateTime('last day of last month');
        $lastDay->setTime(23,59,59);

        return [
            'start_date' => $firsDay,
            'end_date' => $lastDay
        ];
    }

    public static function getMonthsByQuarter($quarter): array
    {
        switch ($quarter) {
            case ('this'):
                $current_month = date('m');
                $current_year = date('Y');
                if($current_month>=1 && $current_month<=3)
                {
                    $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
                }
                else  if($current_month>=4 && $current_month<=6)
                {
                    $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
                    $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
                }
                else  if($current_month>=7 && $current_month<=9)
                {
                    $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
                    $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
                }
                else  if($current_month>=10 && $current_month<=12)
                {
                    $start_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM
                    $end_date = strtotime('1-January-'.($current_year+1));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
                break;
            case ('last'):
                $current_month = date('m');
                $current_year = date('Y');

                if($current_month>=1 && $current_month<=3)
                {
                    $start_date = strtotime('1-October-'.($current_year-1));  // timestamp or 1-October Last Year 12:00:00 AM
                    $end_date = strtotime('1-January-'.$current_year);  // // timestamp or 1-January  12:00:00 AM means end of 31 December Last year
                }
                else if($current_month>=4 && $current_month<=6)
                {
                    $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
                }
                else  if($current_month>=7 && $current_month<=9)
                {
                    $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
                    $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
                }
                else  if($current_month>=10 && $current_month<=12)
                {
                    $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
                    $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
                }
                break;
        }

        return [
            'start_date' => date_create_from_format('Y-m-d H:i',date('Y-m-d H:i',$start_date))->setTime(0,0,0),
            'end_date' => date_create_from_format('Y-m-d H:i',date('Y-m-d H:i',$end_date))->modify("-1 second")
        ];
    }

    public static function getCurrentYearDates(): array
    {
        $startDate = new \DateTime('now');
        $endDate = new \DateTime('now');

        $start_date = $startDate->modify("january")
            ->modify("first day of this month")
            ->modify("midnight");
        $end_date = $endDate->modify("next year")
            ->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->modify("-1 second");

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }

    public static function getLastYearDates(): array
    {
        $startDate = new \DateTime('now');
        $endDate = new \DateTime('now');

        $start_date = $startDate->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->modify("-1 year");
        $end_date = $endDate->modify("next year")
            ->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->modify("-1 second")
            ->modify("-1 year");

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }


    // RETURN DATE FORMAT STRING

    public static function getCurrentMonthDatesFormatString(): array
    {
        $firsDay = new \DateTime('first day of this month');
        $firsDay = $firsDay->format('d.m.Y 00:00');
        $lastDay = new \DateTime('last day of this month');
        $lastDay = $lastDay->format('d.m.Y 23:59');

        return [
            'start_date' => $firsDay,
            'end_date' => $lastDay
        ];
    }

    public static function getLastMonthDatesFormatString(): array
    {
        $firsDay = new \DateTime('first day of last month');
        $firsDay = $firsDay->format('d.m.Y 00:00');
        $lastDay = new \DateTime('last day of last month');
        $lastDay = $lastDay->format('d.m.Y 23:59');

        return [
            'start_date' => $firsDay,
            'end_date' => $lastDay
        ];
    }

    public static function getMonthsByQuarterFormatString($quarter): array
    {
        $array = self::getMonthsByQuarter($quarter);

        return [
            'start_date' => $array['start_date']->format('d.m.Y H:i'),
            'end_date' => $array['end_date']->format('d.m.Y H:i')
        ];
    }

    public static function getCurrentYearDatesFormatString(): array
    {
        $startDate = new \DateTime('now');
        $endDate = new \DateTime('now');

        $start_date = $startDate->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->format('d.m.Y H:i');
        $end_date = $endDate->modify("next year")
            ->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")->modify("-1 second")
            ->format('d.m.Y H:i');

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }

    public static function getLastYearDatesFormatString(): array
    {
        $startDate = new \DateTime('now');
        $endDate = new \DateTime('now');

        $start_date = $startDate->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->modify("-1 year")
            ->format("d.m.Y H:i");
        $end_date = $endDate->modify("next year")
            ->modify("january")
            ->modify("first day of this month")
            ->modify("midnight")
            ->modify("-1 second")
            ->modify("-1 year")
            ->format("d.m.Y H:i");

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }

}
