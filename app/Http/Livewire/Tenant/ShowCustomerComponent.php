<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\Country;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Session\ContactUserSession;
use App\Models\Tenant\Tariff;
use App\Models\Bdgo\CustomerType;
use Cassandra\Custom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Tenancy\Facades\Tenancy;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ShowCustomerComponent extends Component
{
    const DEFAULT_TAB = 'general';
    const DEFAULT_DURATION_MONTH = '1';
    const DURATION_QUARTER = '12';
    const DEFAULT_QUARTER = '1';
    const DEFAULT_YEAR = '1';

    public ?Customer $customer = null;
    public $tab_selected = self::DEFAULT_TAB;
    public $tab_table = 'active';
    public $flag_toastr = false;
    public $planned_operating_time, $prevPlannedOperatingTime;
    public $start_date, $end_date;
    public $duration_months = self::DEFAULT_DURATION_MONTH;
    public $total_planned_operating_time = 0;
    public $total_actual_operating_time = 0;
    public $series_data = [
        'actual_operating_time_data' => [],
        'planned_operating_time_data' => [],
        'total_planned_operating_time' => 0,
        'x_axis_data' => [],
        'total_operating_time_duration' => 0,
        'is_average' => false,
        'total_actual_operating_time' => 0
    ];

    public $customer_name, $prevCustomerName, $email, $prevEmail, $address, $prevAddress, $phone, $prevPhone, $modelId;
    public $country, $city, $post_code, $comment, $website, $prevCountry, $prevCity, $prevPostCode, $prevComment, $prevWebsite;

    public $selectedItem;
    public $active;

    public $billing_addition, $billing_address, $billing_zip_code, $billing_city, $billing_country, $billing_iban, $billing_bic, $billing_email, $billing_payment, $billing_sepa = false, $customer_type_id = Customer::DEFAULT_CUSTOMER_TYPE_ID;
    public $prevBillingAddition, $prevBillingAddress, $prevBillingZipCode, $prevBillingCity, $prevBillingCountry, $prevBillingIban, $prevBillingBic, $prevBillingEmail, $prevBillingPayment, $prevBillingSepa = false, $prevCustomerTypeId;

    public $connection_exists;

    public $quarters, $years = [];

    public $quarter = self::DEFAULT_QUARTER, $year = self::DEFAULT_YEAR;

    public $customerTypes;

    public $listeners = [
        'refresh' => '$refresh',
        'cleanTariffsVars',
    ];

    public function rules(): array
    {
        $rules = [];
        if ($this->modelId) {
            $rules = [
                'customer_name' => ['required', 'min:2', Rule::unique('App\Models\Tenant\Customer')->ignore($this->customer->id, 'id')],
                'email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', Rule::unique('App\Models\Tenant\Customer')->ignore($this->customer->id, 'id')],
                'address' => ['nullable', 'min:6'],
                'phone' => ['nullable'],
                'city' => ['nullable', 'required_with:country'],
                'country' => ['required_with:city'],
                'post_code' => ['nullable', 'min:5'],
                'comment' => ['nullable'],
                'website' => ['nullable', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],

                'billing_addition' => ['nullable', 'min:2'],
                'billing_address' => ['nullable', 'min:2'],
                'billing_zip_code' => ['nullable', 'min:2'],
                'billing_city' => ['nullable', 'required_with:billing_country'],
                'billing_country' => ['required_with:billing_city'],
                'billing_iban' => ['nullable', 'min:2', Rule::unique('App\Models\Tenant\Customer')->ignore($this->customer->id, 'id')],
                'billing_bic' => ['nullable', 'min:2'],
                'billing_email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', Rule::unique('App\Models\Tenant\Customer')->ignore($this->customer->id, 'id')],
                'billing_payment' => ['nullable', 'min:2'],
                'billing_sepa' => ['nullable', 'boolean'],
            ];
        } else {
            $rules = [
                'customer_name' => ['required', 'min:2', 'unique:App\Models\Tenant\Customer'],
                'planned_operating_time' => ['nullable'],
                'email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', 'unique:App\Models\Tenant\Customer'],
                'address' => ['nullable', 'min:6'],
                'phone' => ['nullable'],
                'city' => ['nullable', 'required_with:country'],
                'country' => ['required_with:city'],
                'post_code' => ['nullable', 'min:5'],
                'comment' => ['nullable'],
                'website' => ['nullable', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],

                'billing_addition' => ['nullable', 'min:2'],
                'billing_address' => ['nullable', 'min:2'],
                'billing_zip_code' => ['nullable', 'min:2'],
                'billing_city' => ['nullable', 'required_with:billing_country'],
                'billing_country' => ['required_with:billing_city'],
                'billing_iban' => ['nullable', 'min:2', 'unique:App\Models\Tenant\Customer'],
                'billing_bic' => ['nullable', 'min:2'],
                'billing_email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', 'unique:App\Models\Tenant\Customer'],
                'billing_payment' => ['nullable', 'min:2'],
                'billing_sepa' => ['nullable', 'boolean'],
            ];
        }

        if (APP_EDITION == 'bdgo') {
            $rules['customer_type_id'] = ['required', 'integer'];
        }

        return $rules;
    }

    public function hydrate()
    {
        $this->emit('pikerHydrate');
        $this->emit('inputTimeMasksHydrate');
    }

    public function mount($customer = null)
    {
        $this->tab_selected = request()->get('tab_selected', (auth()->user()->hasRole('Admin')) ?  'statistic' : self::DEFAULT_TAB);
        $this->duration_months = request()->get('duration_months', self::DEFAULT_DURATION_MONTH);
        $this->quarter = request()->get('quarter', self::DEFAULT_QUARTER);
        $this->year = request()->get('year', self::DEFAULT_YEAR);

        $unrelated_contacts_check = auth()->user()->contact_user_session()->get();
        if (count($unrelated_contacts_check)) {
            $unrelated_contacts_check->map(function ($contact_user_session) {
                Contact::query()->find($contact_user_session->contact_id)->delete();
            });
        }
        if (!is_null($customer)) {
            $this->customer = $customer;
            $this->active = $this->customer->active;

            $this->getCustomerModelId($this->customer->id);

            $this->setQuarters();
        }

        if (APP_EDITION == 'bdgo') {
            $this->customerTypes = CustomerType::all();
        }

        if ($this->tab_selected == 'statistic') {
            $this->renderOverviewOperatingTimesChart();
        }
    }

    public function setQuarters()
    {
        $startDate = now()->subMonths(18)->firstOfMonth(); // 1.5 year
        $firstConnectionReport = $this->customer->firstConnectionReport('start_date', '>=', $startDate);

        $createdAt = $this->customer->created_at;

        if (!empty($firstConnectionReport) && !empty($firstConnectionReport->start_date) && $firstConnectionReport->start_date->lt($createdAt)) {
            $createdAt = $firstConnectionReport->start_date;
        }

        $firstOfQuarter = clone $createdAt->firstOfQuarter();

        $lastOfQuarter = clone $createdAt->lastOfQuarter();

        $this->quarters[$createdAt->quarter . $createdAt->format(' - Y')] = [$firstOfQuarter, $lastOfQuarter];

        $this->years[$createdAt->format('Y')] = $createdAt->format('Y');

        $loop = !$lastOfQuarter->isCurrentQuarter();

        while ($loop) {
            $carbon = new Carbon($firstOfQuarter);

            $addQuarter = $carbon->addQuarter();

            $firstOfQuarter = $addQuarter->clone()->firstOfQuarter();

            $lastOfQuarter = $addQuarter->clone()->lastOfQuarter();

            $this->quarters[$addQuarter->quarter . $addQuarter->format(' - Y')] = [$firstOfQuarter, $lastOfQuarter];

            $this->years[$addQuarter->format('Y')] = $addQuarter->format('Y');

            $loop = !$carbon->isCurrentQuarter();
        }
    }

    public function render()
    {
        $container = 'container';

        if (!is_null($this->customer))
            $this->connection_exists = ConnectionReport::where('bdgogid', $this->customer->bdgogid)->count();

        return view('livewire.tenant.show-customer-component')
            ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
            ->section('content');
    }

    public function calculateChartInputDataQuarterly()
    {
        if (empty($this->customer->bdgogid)) {
            return;
        }

        $this->quarter = "1";

        // prepare quarter inputs data
        if (!empty($this->year)) {
            if ($this->year == 1) {
                $quarterStart = reset($this->quarters);
                $quarterEnd   = end($this->quarters);

                $startFrom = (!empty($quarterStart) && is_array($quarterStart)) ? reset($quarterStart) : now()->startOfYear();
                $endTo     = (!empty($quarterEnd) && is_array($quarterEnd)) ? end($quarterEnd) : now()->endOfYear();
            } else {
                $date = Carbon::createFromDate($this->year, 01, 01);

                $startFrom = $date->copy()->startOfYear();
                $endTo     = $date->copy()->endOfYear();
            }
        } else {
            $startFrom = now()->startOfYear();
            $endTo     = now()->endOfYear();

            $this->year = $startFrom->format('Y');
        }

        $periods   = CarbonPeriod::create($startFrom, '3 month', $endTo);

        foreach ($periods as $index => $date) {
            $inputs[$index]['start'] = $date->format('d.m.Y');
            $inputs[$index]['quarter'] = $date->quarter;
            $inputs[$index]['end'] = $date->lastOfQuarter()->format('d.m.Y');
        }

        $quarterData = $plannedOperationTimeData = [];
        foreach ($inputs as $month) {
            $connectionReports = ConnectionReport::group($this->customer->bdgogid)
                ->status(1)
                ->whereNotNull('tariff_id')
                ->whereDate('start_date', '>=', $this->filterByStartDate($month['start']))
                ->whereDate('end_date', '<=', $this->filterByEndDate($month['end']))
                ->orderBy('start_date')
                ->get()
                ->groupBy(function ($item) {
                    return $item->start_date->format('Y-m-d');
                });

            $actualOperationTimeData = $actualPlannedOperationData = [];

            foreach ($connectionReports as $connections) {
                $durationByDay = 0;

                foreach ($connections as $connection) {
                    $durationByDay += $connection->duration();
                }

                $actualOperationTimeData[] = $durationByDay;

                $actualPlannedOperationData[$connection->start_date->format('m-Y')] = Helper::convertToMinutes($this->customer->getPlannedOperatingTime($connection->start_date));
            }

            // Add remaining months POT if not found from connection_reports.
            if (count($actualPlannedOperationData) < 3) {
                for ($i = count($actualPlannedOperationData); $i < 3; $i++) {
                    $actualPlannedOperationData[] = Helper::convertToMinutes($this->customer->planned_operating_time);
                }
            }

            if (!count($actualOperationTimeData)) {
                $actualOperationTimeData[] = 0;
            }

            $quarterData['Q' . $month['quarter'] . ' ' . substr($month['start'], '6', '4')] = array_sum($actualOperationTimeData);

            $plannedOperationTimeData['Q' . $month['quarter'] . ' ' . substr($month['start'], '6', '4')] = array_sum($actualPlannedOperationData);
        }

        // Quarterly multiply by 3.
        // Because as per Susann we define planned operation time monthly so we have to calculate it by 3.
        /* Not needed as we already calculate POT month wise. */
        // foreach ($plannedOperationTimeData as &$plannedOperationTime) {
        //     $plannedOperationTime = ($plannedOperationTime * 3);
        // }

        // Set planned operating time for calculate average.
        $plannedOperationTimeTotal   = array_sum($plannedOperationTimeData);
        $plannedOperationTimeAverage = collect($plannedOperationTimeData)->avg();

        $actualOperationTimeTotal   = array_sum($quarterData);
        $actualOperationTimeAverage = collect($quarterData)->avg();

        foreach ($quarterData as &$actualOperationTime) {
            $actualOperationTime = Helper::convertMinsToHoursMins($actualOperationTime, ".");
        }

        foreach ($plannedOperationTimeData as &$plannedOperationTime) {
            $plannedOperationTime = Helper::convertMinsToHoursMins($plannedOperationTime, '.');
        }

        $this->series_data = [
            'actual_operating_time_data' => array_values($quarterData),
            'planned_operating_time_data' => array_values($plannedOperationTimeData),
            'total_planned_operating_time' => ($this->year != self::DEFAULT_YEAR) ? Helper::convertMinsToHoursMins($plannedOperationTimeAverage, ':') : Helper::convertMinsToHoursMins($plannedOperationTimeTotal, ':'),
            'x_axis_data' => array_keys($quarterData),
            'total_operating_time_duration' => (($this->duration_months == self::DEFAULT_DURATION_MONTH) ? ($actualOperationTimeTotal - $plannedOperationTimeTotal) : ($actualOperationTimeTotal - $plannedOperationTimeAverage)),
            'is_average' => true,
            'total_actual_operating_time' => $actualOperationTimeAverage
        ];
    }

    public function calculateChartInputDataMonthly()
    {
        if (empty($this->customer->bdgogid)) {
            return;
        }

        $this->year = "1";

        $this->start_date = now()->subMonths($this->duration_months)->format('d.m.Y');
        $this->end_date = now()->format('d.m.Y');

        $actualOperationTimeData = [];
        $plannedOperationTimeData = [];

        if (!empty($this->quarter) && $this->quarter == 1) {
            $quarterStart = reset($this->quarters);
            $quarterEnd   = end($this->quarters);

            $startFrom = (!empty($quarterStart) && is_array($quarterStart)) ? reset($quarterStart) : now()->startOfYear();
            $endTo     = (!empty($quarterEnd) && is_array($quarterEnd)) ? end($quarterEnd) : now()->endOfYear();
        } elseif (!empty($this->quarter) && !empty($this->quarters[$this->quarter])) {
            $quarter = $this->quarters[$this->quarter];

            $startFrom = reset($quarter);
            $endTo     = end($quarter);
        } else {
            $startFrom = now()->startOfYear();
            $endTo     = now()->endOfYear();

            $this->quarter = $startFrom->quarter . $startFrom->format(' - Y');
        }

        $periods   = CarbonPeriod::create($startFrom, '1 month', $endTo);

        foreach ($periods as $index => $date) {
            $start_date = $date->startOfMonth()->format('Y-m-d 00:00:00');
            $end_date = $date->endOfMonth()->format('Y-m-d 23:59:59');
            $connectionReports = ConnectionReport::group($this->customer->bdgogid)
                ->status(1)
                ->whereNotNull('tariff_id')
                ->where('start_date', '>=', $this->filterByStartDate($start_date))
                ->where('end_date', '<=', $this->filterByEndDate($end_date))
                ->orderBy('start_date')
                ->get()
                ->groupBy(function ($item) {
                    return $item->start_date->format('Y-m-d');
                });

            foreach ($connectionReports as $connections) {
                $durationByDay = 0;

                foreach ($connections as $connection) {
                    $durationByDay += $connection->duration();
                }

                if (!empty($actualOperationTimeData[$date->startOfMonth()->format('m-Y')])) {
                    $actualOperationTimeData[$date->startOfMonth()->format('m-Y')] += $durationByDay;
                } else {
                    $actualOperationTimeData[$date->startOfMonth()->format('m-Y')] = $durationByDay;
                }

                $plannedOperationTimeData[$date->startOfMonth()->format('m-Y')] = Helper::convertToMinutes($this->customer->getPlannedOperatingTime($date));
            }
        }

        if (!count($actualOperationTimeData)) {
            $actualOperationTimeTotal   = 0;
            $actualOperationTimeAverage = 0;
            $actualOperationTimeData[]  = 0;

            $plannedOperationTimeTotal   = 0;
            $plannedOperationTimeAverage = 0;
            $plannedOperationTimeData[]  = 0;

            $totalPlannedOperatingTime = 0;
        } else {
            $actualOperationTimeTotal = array_sum($actualOperationTimeData);

            $actualOperationTimeAverage = collect($actualOperationTimeData)->avg();

            $plannedOperationTimeTotal = array_sum($plannedOperationTimeData);

            $plannedOperationTimeAverage = collect($plannedOperationTimeData)->avg();

            foreach ($actualOperationTimeData as &$actualOperationTime) {
                $actualOperationTime = Helper::convertMinsToHoursMins($actualOperationTime, ".");
            }

            // sort by month
            uksort($actualOperationTimeData, function($a, $b) {
                return strtotime('01-'.$a) - strtotime('01-'.$b);
            });

            // sort by year
            uksort($actualOperationTimeData, function($a, $b) {
                return strtotime('01-01-'.explode('-',$a)[1]) - strtotime('01-01-'.explode('-',$b)[1]);
            });

            if ($this->quarter != self::DEFAULT_QUARTER) {
                foreach ($plannedOperationTimeData as $plannedOperationTime) {
                    $totalPlannedOperatingTime = ($plannedOperationTime * 3);
                }
            } else {
                $totalPlannedOperatingTime = $plannedOperationTimeTotal;
            }

            foreach ($plannedOperationTimeData as &$plannedOperationTime) {
                $plannedOperationTime = Helper::convertMinsToHoursMins($plannedOperationTime, '.');
            }
        }

        $this->series_data = [
            'actual_operating_time_data' => array_values($actualOperationTimeData),
            'planned_operating_time_data' => array_values($plannedOperationTimeData),
            'total_planned_operating_time' => Helper::convertMinsToHoursMins($totalPlannedOperatingTime, ':'),
            'x_axis_data' => array_keys($actualOperationTimeData),
            'is_average' => ($this->quarter == self::DEFAULT_QUARTER),
            'total_actual_operating_time' => ($this->quarter == self::DEFAULT_QUARTER) ? $actualOperationTimeAverage : $actualOperationTimeTotal,
            'total_operating_time_duration' => (($this->duration_months == self::DEFAULT_DURATION_MONTH) ? ($actualOperationTimeTotal - $plannedOperationTimeTotal) : ($actualOperationTimeTotal - $plannedOperationTimeAverage))
        ];
    }

    public function calculateChartInputsData()
    {
        if ($this->duration_months == 12) {
            $this->calculateChartInputDataQuarterly();
        } elseif ($this->duration_months == 1) {
            $this->calculateChartInputDataMonthly();
        }
    }

    public function filterByStartDate($value)
    {
        $start_time = new \DateTime($value);
        $start_time = $start_time->format('d.m.Y 00:00:00');
        return date_create($start_time);
    }

    public function filterByEndDate($value)
    {
        $end_time = new \DateTime($value);
        $end_time = $end_time->format('d.m.Y 23:59:59');
        return date_create($end_time);
    }

    public function updated($customer_name)
    {
        $this->validateOnly($customer_name, $this->rules());

        if (empty($this->city) && $this->getErrorBag()->has('country'))
            $this->resetErrorBag(['country']);
        if (empty($this->country && $this->getErrorBag()->has(['city'])))
            $this->resetErrorBag(['city']);
    }

    public function getCustomerModelId($modelId)
    {
        $this->modelId = $modelId;
        $model = Customer::find($this->modelId);
        $this->customer_name = $model->customer_name;
        $this->planned_operating_time = $model->planned_operating_time;
        $this->email = $model->email;
        $this->address = $model->address;
        $this->phone = $model->phone;
        $this->city = $model->city;
        $this->country = $model->country;
        $this->post_code = $model->post_code;
        $this->comment = $model->comment;
        $this->website = $model->website;

        $this->billing_addition = $model->billing_addition;
        $this->billing_address = $model->billing_address;
        $this->billing_zip_code = $model->billing_zip_code;
        $this->billing_city = $model->billing_city;
        $this->billing_country = $model->billing_country;
        $this->billing_iban = $model->billing_iban;
        $this->billing_bic = $model->billing_bic;
        $this->billing_email = $model->billing_email;
        $this->billing_payment = $model->billing_payment;
        $this->billing_sepa = $model->billing_sepa;

        if (APP_EDITION == 'bdgo') {
            $this->customer_type_id = (!empty($model->customer_type_id) ? $model->customer_type_id : Customer::DEFAULT_CUSTOMER_TYPE_ID);
        }

        $this->prevCustomerName = $model->customer_name;
        $this->prevPlannedOperatingTime = $model->planned_operating_time;
        $this->prevEmail = $model->email;
        $this->prevAddress = $model->address;
        $this->prevPhone = $model->phone;
        $this->prevCity = $model->city;
        $this->prevCountry = $model->country;
        $this->prevPostCode = $model->post_code;
        $this->prevComment = $model->comment;
        $this->prevWebsite = $model->website;

        $this->prevBillingAddition = $model->billing_addition;
        $this->prevBillingAddress = $model->billing_address;
        $this->prevBillingZipCode = $model->billing_zip_code;
        $this->prevBillingCity = $model->billing_city;
        $this->prevBillingCountry = $model->billing_country;
        $this->prevBillingIban = $model->billing_iban;
        $this->prevBillingBic = $model->billing_bic;
        $this->prevBillingEmail = $model->billing_email;
        $this->prevBillingPayment = $model->billing_payment;
        $this->prevBillingSepa = $model->billing_sepa;

        if (APP_EDITION == 'bdgo') {
            $this->prevCustomerTypeId = $model->customer_type_id;
        }
    }

    public function save($order)
    {
        $this->store();
        if ($order == 'new') {
            if ($this->flag_toastr) {
                $this->tab_selected = 'general';
                $this->dispatchBrowserEvent('focusInput');
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Created!')]);
            }
        } else {
            if ($this->modelId) {
                // $this->modelId = null;
                if ($this->flag_toastr) {
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Updated!')]);
                }
            } else {
                if ($this->flag_toastr) {
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Created!')]);
                }
            }
        }
        $this->reset('flag_toastr');
    }

    public function store()
    {
        $attributes = [
            'customer_name' => $this->customer_name,
            'planned_operating_time' => $this->planned_operating_time,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'city' => $this->city,
            'country' => $this->country,
            'post_code' => $this->post_code,
            'comment' => $this->comment,
            'website' => $this->website,

            'billing_addition' => $this->billing_addition,
            'billing_address' => $this->billing_address,
            'billing_zip_code' => $this->billing_zip_code,
            'billing_city' => $this->billing_city,
            'billing_country' => $this->billing_country,
            'billing_iban' => $this->billing_iban,
            'billing_bic' => $this->billing_bic,
            'billing_email' => $this->billing_email,
            'billing_payment' => $this->billing_payment,
            'billing_sepa' => $this->billing_sepa
        ];

        if (APP_EDITION == 'bdgo') {
            $attributes['customer_type_id'] = $this->customer_type_id;
        }

        $validation = Validator::make($attributes, $this->rules());

        if ($validation->fails()) {
            $errorMsg = $validation->getMessageBag();
            if ($errorMsg->has(['customer_name']) || $errorMsg->has(['email'])
                || $errorMsg->has(['address']) || $errorMsg->has(['phone'])
                || $errorMsg->has(['city']) || $errorMsg->has(['country'])
                || $errorMsg->has(['post_code']) || $errorMsg->has(['website'])) {
                $this->tab_selected = 'general';
            } else {
                $this->tab_selected = 'billing';
            }
            $this->dispatchBrowserEvent('focusErrorInput', ['field' => array_key_first($errorMsg->getMessages())]);
            $validation->validate();
        }
        $data = [];
        $validateData = [];
        if ($this->modelId) {
            if ($this->customer_name !== $this->prevCustomerName) {
                $data = array_merge($data, ['customer_name' => $this->customer_name]);
                $validateData = array_merge($validateData, [
                    'customer_name' => ['required', 'min:2', Rule::unique('App\Models\Tenant\Customer')->ignore($this->customer->id, 'id')],
                ]);
            }
            if ($this->planned_operating_time !== $this->prevPlannedOperatingTime) {

                // format planned operating time
                if (!str_contains($this->planned_operating_time, ':') && !str_contains($this->planned_operating_time, ',')) {
                    if (str_contains($this->planned_operating_time, '.')) {
                        [$hours, $minutes] = explode('.', $this->planned_operating_time);
                        if ($minutes > 59) {
                            $hours = floor($minutes / 60) + $hours;
                            $minutes = ($minutes % 60);
                        }
                        $this->planned_operating_time = ($hours * 60) + $minutes;
                    }
                    $this->planned_operating_time = !empty($this->planned_operating_time) ? \App\Helpers\Helper::formatHoursAndMinutes($this->planned_operating_time, '%02d:%02d', true)
                        : 0;
                }

                $data = array_merge($data, ['planned_operating_time' => $this->planned_operating_time]);
                $validateData = array_merge($validateData, [
                    'planned_operating_time' => ['nullable'],
                ]);
            }
            if ($this->email !== $this->prevEmail) {
                $data = array_merge($data, ['email' => $this->email]);
                $validateData = array_merge($validateData, [
                    'email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', Rule::unique('App\Models\Tenant\Customer')->ignore($this->modelId, 'id')],
                ]);
            }
            if ($this->address !== $this->prevAddress) {
                $data = array_merge($data, ['address' => $this->address]);
                $validateData = array_merge($validateData, [
                    'address' => ['nullable', 'min:6'],
                ]);
            }
            if ($this->phone !== $this->prevPhone) {
                $data = array_merge($data, ['phone' => $this->phone]);
                $validateData = array_merge($validateData, [
                    'phone' => ['nullable', Rule::unique('App\Models\Tenant\Customer')->ignore($this->modelId, 'id')],
                ]);
            }
            if ($this->city !== $this->prevCity) {
                $data = array_merge($data, ['city' => $this->city]);
                $validateData = array_merge($validateData, [
                    'city' => ['nullable', 'required_with:country']
                ]);
            }
            if ($this->country !== $this->prevCountry) {
                $data = array_merge($data, ['country' => $this->country]);
                $validateData = array_merge($validateData, [
                    'country' => ['required_with:city'],
                ]);
            }
            if ($this->post_code !== $this->prevPostCode) {
                $data = array_merge($data, ['post_code' => $this->post_code]);
                $validateData = array_merge($validateData, [
                    'post_code' => ['nullable', 'min:5']
                ]);
            }
            if ($this->comment !== $this->prevComment) {
                $data = array_merge($data, ['comment' => $this->comment]);
                $validateData = array_merge($validateData, [
                    'comment' => ['nullable']
                ]);
            }
            if ($this->website !== $this->prevWebsite) {
                $data = array_merge($data, ['website' => $this->website]);
                $validateData = array_merge($validateData, [
                    'website' => ['nullable', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/']
                ]);
            }

            if ($this->billing_addition !== $this->prevBillingAddition) {
                $data = array_merge($data, ['billing_addition' => $this->billing_addition]);
                $validateData = array_merge($validateData, [
                    'billing_addition' => ['nullable', 'min:2']
                ]);
            }
            if ($this->billing_address !== $this->prevBillingAddress) {
                $data = array_merge($data, ['billing_address' => $this->billing_address]);
                $validateData = array_merge($validateData, [
                    'billing_address' => ['nullable', 'min:2']
                ]);
            }
            if ($this->billing_zip_code !== $this->prevBillingZipCode) {
                $data = array_merge($data, ['billing_zip_code' => $this->billing_zip_code]);
                $validateData = array_merge($validateData, [
                    'billing_zip_code' => ['nullable', 'min:2']
                ]);
            }
            if ($this->billing_city !== $this->prevBillingCity) {
                $data = array_merge($data, ['billing_city' => $this->billing_city]);
                $validateData = array_merge($validateData, [
                    'billing_city' => ['nullable', 'required_with:billing_country']
                ]);
            }
            if ($this->billing_country !== $this->prevBillingCountry) {
                $data = array_merge($data, ['billing_country' => $this->billing_country]);
                $validateData = array_merge($validateData, [
                    'billing_country' => ['required_with:billing_city']
                ]);
            }
            if ($this->billing_iban !== $this->prevBillingIban) {
                $data = array_merge($data, ['billing_iban' => $this->billing_iban]);
                $validateData = array_merge($validateData, [
                    'billing_iban' => ['nullable', 'min:2', Rule::unique('App\Models\Tenant\Customer', 'email')->ignore($this->modelId, 'id')]
                ]);
            }
            if ($this->billing_bic !== $this->prevBillingBic) {
                $data = array_merge($data, ['billing_bic' => $this->billing_bic]);
                $validateData = array_merge($validateData, [
                    'billing_bic' => ['nullable', 'min:2']
                ]);
            }
            if ($this->billing_email !== $this->prevBillingEmail) {
                $data = array_merge($data, ['billing_email' => $this->billing_email]);
                $validateData = array_merge($validateData, [
                    'billing_email' => ['nullable', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', Rule::unique('App\Models\Tenant\Customer', 'email')->ignore($this->modelId, 'id')]
                ]);
            }
            if ($this->billing_payment !== $this->prevBillingPayment) {
                $data = array_merge($data, ['billing_payment' => $this->billing_payment]);
                $validateData = array_merge($validateData, [
                    'billing_payment' => ['nullable', 'min:2']
                ]);
            }
            if ($this->billing_sepa !== $this->prevBillingSepa) {
                $data = array_merge($data, ['billing_sepa' => $this->billing_sepa]);
                $validateData = array_merge($validateData, [
                    'billing_sepa' => ['boolean']
                ]);
            }
            if (APP_EDITION == 'bdgo' && $this->customer_type_id != $this->prevCustomerTypeId) {
                $data = array_merge($data, ['customer_type_id' => $this->customer_type_id]);

                $validateData = array_merge($validateData, [
                    'customer_type_id' => ['required', 'integer']
                ]);
            }

            $validation = Validator::make($data, $validateData)->validate();

            if (count($validation)) {
                $customer = Customer::find($this->modelId);
                $customer->update($validation);
                $this->flag_toastr = true;

                $this->getCustomerModelId($this->modelId);
            }
        } else {
            $groupId = $this->getGroupIdGenerated();
            $customer = Customer::create($validation->validated() + ['bdgogid' => $groupId]);
            $contacts_check = auth()->user()->contact_user_session()->where('session_id', session()->getId())->pluck('contact_id');
            if (count($contacts_check)) {
                $contacts_delete = auth()->user()->contact_user_session()->where('session_id', session()->getId())->get();
                $contacts = Contact::query()->whereNull('bdgo_gid')->whereIn('id', $contacts_check)->get();
                $contacts->map(function ($contact) use ($groupId) {
                    $contact->update(['bdgo_gid' => $groupId]);
                });
                $contacts_delete->map(function ($contact) {
                    $contact->delete();
                });
            }
            $this->flag_toastr = true;
            session(['customerCreated' => $customer->id]);
        }
    }

    public function getGroupIdGenerated(): string
    {
        $randomGroupId = $this->generateRandomOwnGroupId();
        if (Customer::query()->where('bdgogid', $randomGroupId)->exists())
            $this->getGroupIdGenerated();
        else
            return $randomGroupId;
    }

    public function generateRandomOwnGroupId(): string
    {
        return 't' . Tenancy::getTenant()->getTenantKey() . '-g' . strtolower($this->generateRandomString(9));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('123456789'), 1, $length);
    }

    public function fillFromGeneral()
    {
        $this->billing_city = $this->city;
        $this->billing_country = $this->country;
        $this->billing_address = $this->address;
        $this->billing_zip_code = $this->post_code;
        $this->billing_email = $this->email;
    }

    public function cleanVars()
    {
        $this->customer_name = null;
        $this->email = null;
        $this->address = null;
        $this->phone = null;
        $this->city = null;
        $this->country = null;
        $this->post_code = null;
        $this->comment = null;
        $this->website = null;

        $this->billing_addition = null;
        $this->billing_address = null;
        $this->billing_zip_code = null;
        $this->billing_city = null;
        $this->billing_country = null;
        $this->billing_iban = null;
        $this->billing_bic = null;
        $this->billing_email = null;
        $this->billing_payment = null;
        $this->reset(['billing_sepa']);

        $this->prevCustomerName = null;
        $this->prevEmail = null;
        $this->prevAddress = null;
        $this->prevPhone = null;
        $this->prevCity = null;
        $this->prevCountry = null;
        $this->prevPostCode = null;
        $this->prevComment = null;
        $this->prevWebsite = null;

        $this->prevBillingAddition = null;
        $this->prevBillingAddress = null;
        $this->prevBillingZipCode = null;
        $this->prevBillingCity = null;
        $this->prevBillingCountry = null;
        $this->prevBillingIban = null;
        $this->prevBillingBic = null;
        $this->prevBillingEmail = null;
        $this->prevBillingPayment = null;
        $this->reset(['prevBillingSepa']);
    }

    public function cancel()
    {
        $this->cleanVars();
        return redirect('/customers/list');
    }

    public function renderOverviewOperatingTimesChart()
    {
        $this->calculateChartInputsData();
        $this->dispatchBrowserEvent('renderOverviewOperatingTimesChart');
    }

    public function renderHourMinutePicker()
    {
        $this->dispatchBrowserEvent('initHourMinutePicker');
    }

    public function updatedDurationMonths()
    {
        if ($this->tab_selected == 'statistic') {
            $this->renderOverviewOperatingTimesChart();
        }
    }

    public function updatedTabSelected($value)
    {
        if ($value == 'statistic') {
            $this->renderOverviewOperatingTimesChart();
        } elseif ($value == 'general') {
            $this->renderHourMinutePicker();
        }
    }

    public function updatedQuarter()
    {
        if ($this->tab_selected == 'statistic') {
            $this->renderOverviewOperatingTimesChart();
        }
    }

    public function updatedYear()
    {
        if ($this->tab_selected == 'statistic') {
            $this->renderOverviewOperatingTimesChart();
        }
    }

    public function updatedActive($val)
    {
        $this->customer->update(['active' => !$val]);
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => $val ? __('locale.Customer Activated!') : __('locale.Customer Deactivated!')]);
    }
}
