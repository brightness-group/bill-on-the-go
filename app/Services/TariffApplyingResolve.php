<?php

namespace App\Services;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Tariff;
use Illuminate\Database\Eloquent\Collection;

class TariffApplyingResolve
{

    protected static Tariff $tariff;
    private static bool $permanent = false;
    private static ?string $bdgogid = null;

    public function __construct(Tariff $tariff, array $attributes)
    {
        self::$tariff = $tariff;
        if (array_key_exists('permanent',$attributes)) {
            if ($attributes['permanent'])
                self::$permanent = true;
        }
        if (array_key_exists('bdgogid',$attributes)) {
            self::$bdgogid = $attributes['bdgogid'];
        }
    }

    public static function applyTariffToConnections($checkOverlap = false)
    {
        $connections = Collection::empty();
        if (self::$tariff->global) {
            $customTariffs = Tariff::where('global', false)->pluck('id');
            if (self::$permanent) {
                $connections = ConnectionReport::where('start_date','>=',self::convertDateTime(self::$tariff->start_period))
                                               ->where('tariff_id',null)
                                               ->orWhere('tariff_id','!=',null)
                                               ->whereNotIn('tariff_id', $customTariffs)->get();
            } elseif (!self::$permanent) {
                $connections = ConnectionReport::where('start_date','>=',self::convertDateTime(self::$tariff->start_period))
                                                ->where('end_date','<=',self::convertDateTime(self::$tariff->end_period))
                                                ->where('tariff_id',null)
                                                ->orWhere('tariff_id','!=',null)
                                                ->whereNotIn('tariff_id', $customTariffs)->get();
            }

        } else {
            if (self::$bdgogid) {
                $group = Customer::find(self::$bdgogid);
                if ($group) {
                    $customTariffs = $group->tariffs->whereNotIn('id',self::$tariff->id)->pluck('id');
                    if (self::$permanent) {
                        $connections = ConnectionReport::where('bdgogid',self::$bdgogid)
                            ->where('start_date','>=',self::convertDateTime(self::$tariff->start_period))
                            ->whereNotIn('tariff_id',$customTariffs)->get();
                    } elseif (!self::$permanent) {
                        $connections = ConnectionReport::where('bdgogid',self::$bdgogid)
                            ->where('start_date','>=',self::convertDateTime(self::$tariff->start_period))
                            ->where('end_date','<=',self::convertDateTime(self::$tariff->end_period))
                            ->whereNotIn('tariff_id',$customTariffs)->get();
                    }
                }
            }
        }
        if (count($connections)) {
            foreach ($connections as $connection) {
                if (self::checkForTariffRelations(self::$tariff, $connection)) {
                    $connection->tariff_id = self::$tariff->id;
                    $connection->save();

                    $price = $connection->calculatePrice();
                    $connection->update(['price' => $price]);
                }
                /*if ($checkOverlap) {
                    $exec = new OverlapsEvaluation($connection);
                    $exec::overlaps_check();
                }*/
            }
        }
        return $connections;
    }

    private static function convertDateTime($datetime)
    {
        return !$datetime instanceof \DateTime ? date_create_from_format('Y-m-d H:i:s',$datetime) : $datetime;
    }

    public static function editedTariffToConnections()
    {
        $connections = self::$tariff->connections;

        foreach ($connections as $connection) {
            if (self::checkForTariffRelations(self::$tariff, $connection)) {
                $connection->tariff_id = self::$tariff->id;
                $connection->save();

                $price = $connection->calculatePrice();
                $connection->update(['price' => $price]);
            } else {
                $connection->tariff_id = null;
                $connection->price = null;
                $connection->save();
            }
        }
        $editedConnections = $connections->pluck('id');
        $customTariffs = Tariff::where('global',false)->pluck('id');
        $researchConnections = new Collection();
        if (self::$bdgogid) {
            if (self::$permanent) {
                $researchConnections = ConnectionReport::all()
                                                ->where('bdgogid',self::$bdgogid)
                                                ->whereNotIn('id',$editedConnections)
                                                ->where('start_date','>=',self::$tariff->start_period);
            } else {
                $researchConnections = ConnectionReport::all()
                                                ->where('bdgogid',self::$bdgogid)
                                                ->whereNotIn('id',$editedConnections)
                                                ->where('start_date','>=',self::$tariff->start_period)
                                                ->where('end_date','<=',self::$tariff->end_period);
            }
        } else {
            if (self::$permanent) {
                $researchConnections = ConnectionReport::all()
                                                ->whereNotIn('id',$editedConnections)
                                                ->whereNotIn('tariff_id',$customTariffs)
                                                ->where('start_date','>=',self::$tariff->start_period);
            } else {
                $researchConnections = ConnectionReport::all()
                                                ->whereNotIn('id',$editedConnections)
                                                ->whereNotIn('tariff_id',$customTariffs)
                                                ->where('start_date','>=',self::$tariff->start_period)
                                                ->where('end_date','<=',self::$tariff->end_period);
            }
        }

        foreach ($researchConnections as $connection) {
            if (self::checkForTariffRelations(self::$tariff, $connection)) {
                $connection->tariff_id = self::$tariff->id;
                $connection->save();

                $price = $connection->calculatePrice();
                $connection->update(['price' => $price]);
            }
        }
    }

    public static function calculatePriceOnTariffChangeValues()
    {
        $connections = self::$tariff->connections;

        foreach ($connections as $connection) {
            if (self::checkForTariffRelations(self::$tariff, $connection)) {
                $price = $connection->calculatePrice();
                $connection->update(['price' => $price]);
            } else {
                $connection->price = null;
                $connection->save();
            }
        }
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
                $connStart = strtotime($connection['start_date']->format('H:i'));
                $connEnd = strtotime($connection['end_date']->format('H:i'));
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
