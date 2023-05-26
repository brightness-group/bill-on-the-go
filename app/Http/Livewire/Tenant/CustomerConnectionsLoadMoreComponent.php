<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Device;
use App\Models\Tenant\Livetrack;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\Tariff;
use App\Services\BorderLineConnectionWatcher;
use App\Services\ConnectionRecoveryService;
use App\Services\DatePeriodsCalculate;
use App\Services\OverlapsEvaluation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade as PDF;
use Tenancy\Facades\Tenancy;
use function Livewire\str;

class CustomerConnectionsLoadMoreComponent extends Component
{
    public ?Customer $customer = null;

    public $show = false;

    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $headers, $modalHeaders, $itemsHidden;
    public $sortColumn = 'start_date';
    public $sortDirection = 'desc';
    public $searchTerm = '';
    public $bulk = 100000000;
    public $loadMoreNumber = 100;
    public $pageNumber;
    private $loadMore = false;

    public $customers;
    public $selectedCustomer = '';

    public $users;
    public $selectedUser = '';

    public $billings;
    public $selectedStatus = '';

    public $devices;
    public $selectedDevice = '';

    public $selectedContactType = '';

    public $tariffs;
    public $selectedTariff = '';

    public $outCalendarRange = false;
    public $selectedCalendar = 1;
    public $prevSelectedCalendar = 1;
    public $start_date, $end_date;

    public $connectionsIncome;
    public $hasMorePage = false;

    public $counter = false;
    public $counterStart, $counterEnd;

    public $toastrMessageRedirect;

    public $printView = false;

    public $isLoadMore = false;

    public $selectAllCheckboxes = false;
    public $selectedRowCheckbox = [];
    public $unSelectedRowCheckbox = [];

    protected $listeners = [
        'retrievedDataFromAPITeamViewer' => '$refresh',
        'printViewGeneratorEvent' => '$refresh',
        'shownContactModal' => 'openContactModal',
        'shownCustomerModal' => 'openCustomerModal',
        'hideContactModal'=>'closeContactModal',
        'shownDeviceModal' => 'openDeviceModal',
        'hideDeviceModal'=>'closeDeviceModal',
        'shownUserModal' => 'openUserModal',
        'hideUserModal'=>'closeUserModal',
        'hideCustomerModal'=>'closeCustomerModal',
        'refresh' => '$refresh',
        'loadMoreForConnectionsReport',
        'redirectToastrMessages',
        'stopChrono_redirected',
        'recovery_connection_process',
        'stopChronoValue',
        'stopChronos',
        'selectDevice',
        'selectChange',
        'loadMoreUpdatePage',
    ];

    public function hydrate()
    {
        $this->dispatchBrowserEvent('updateTableDataWidth');
    }

    public function mount($customer = null)
    {
        $this->pageNumber = $this->pageNumber ?? 1;
        if(session()->has('todo_view_action_type')){
            $this->selectedStatus = session('todo_view_action_type') == 'tariff-overlapping' ? 6 : 5;
            $this->selectedCalendar = null;
            session()->remove('todo_view_action_type');
        }
        if (session()->has('counterDataStored')) {
            $data = session()->get('counterDataStored');
            if (count($data)) {
                if (key_exists('counter', $data))
                    $this->counter = $data['counter'];
                if (key_exists('counterStart', $data))
                    $this->counterStart = $data['counterStart'];
                if (key_exists('customer', $data))
                    $this->customer = $data['customer'] ? Customer::query()->where('bdgogid',$data['customer'])->first() : null;
            }
        } elseif (session()->has('recovery_connection_process')) {
            $model = auth()->user()->connection_recovery()->first();
            if ($model) {
                $this->customer = $model->bdgo_gid ? Customer::query()->where('bdgogid',$model->bdgo_gid)->first() : null;
                $this->counterStart = $model->start_date;
                $this->counterEnd = $model->end_date;
            } else session()->forget('recovery_connection_process');
        }
        if (!$this->customer)
            $this->customer = $customer;
        if (session()->has('borderLimitSelect')) {
            $this->toastrMessageRedirect = session()->get('borderLimitSelect');
            session()->forget('borderLimitSelect');
        }
        $this->modalHeaders = $this->modalHeaderConfig();
        $this->initDates();
        $this->initCustomers();
        $this->loadData();
    }

    public function render()
    {
        $this->dispatchBrowserEvent('loadMoreConnectionSuccess');
        if ($this->loadMore) {
            return view('livewire.tenant.customer-connections-load-more-component-partial', [
                'connections' => $this->filterBySelectedInputs(),
                'totalAmount' => $this->calculateTotalIncome(),
                'totalDuration' => $this->calculateTotalDuration(),
                'totalUnits' => $this->calculateTotalUnits(),
                'pageNumber' => $this->pageNumber,
            ]);
        } else {
            return view('livewire.tenant.customer-connections-load-more-component',[
                'connections' => $this->filterBySelectedInputs(),
            ]);
        }
    }

    public function loadMoreForConnectionsReport()
    {
        $this->loadMore = true;
        $this->pageNumber += 1;
    }

    public function updatingBulk($value)
    {
        if ((int)$value > 1000) {
            $this->isLoadMore = true;
            $this->reset(['loadMoreNumber']);
        } else $this->isLoadMore = false;
    }

    public function updatedSelectedCustomer($value)
    {
        $this->selectedCustomer = $value;
        $this->customer = $this->selectedCustomer ? Customer::where('bdgogid',$this->selectedCustomer)->first() : null;

        if ($this->printView) {
            $data = $this->generatePDF('print-view');
            if (!empty($data) && (count($data['charged']) || count($data['notCharged']))) {
                $this->selectedCalendar = 2;
                $this->initDates();
                $this->bulk = 100000000;
            } else {
                $this->reset(['printView', 'bulk']);
                $this->initDates();
            }
        } else {
            $this->selectedUser = '';
            $this->selectedDevice = '';
            $this->selectedTariff = '';
            $this->loadData();
        }
    }

    public function updatedSelectedCalendar()
    {
        $this->initDates();
    }

    public function selectDevice($data)
    {
        $this->selectedDevice = $data;
    }

    public function selectCalendarOption($value)
    {
        $this->prevSelectedCalendar = $value;
    }

    public function loadData()
    {
        $this->headers = $this->headerConfig();
        $this->initUsers();
        $this->initDevices();
        $this->initTariffs();
    }

    public function updatedStartDate($value)
    {
        if ($value == "") {
            $this->validate(['start_date' => 'required_with:end_date']);
        } else {
            $validator = Validator::make([
                'start_date' => $this->filterByStartDate($this->start_date),
                'end_date' => $this->filterByEndDate($this->end_date)
            ],[
                'start_date' => ['required','date','before_or_equal:end_date'],
                'end_date' => ['required','date','after_or_equal:start_date']
            ]);
            if ($validator->fails()) {
                $validator->validate();
            } elseif ($this->start_date && $this->end_date) {
                $this->evalPeriodRangeForCalendar();
            }
        }
    }

    public function updatedEndDate($value)
    {
        if ($value == "")
            $this->validate(['end_date' => 'required_with:start_date']);
        else {
            $validator = Validator::make([
                'start_date' => $this->filterByStartDate($this->start_date),
                'end_date' => $this->filterByEndDate($this->end_date)
            ],[
                'start_date' => 'date|before_or_equal:end_date',
                'end_date' => 'date|after_or_equal:start_date|required_with:start_date'
            ]);
            if ($validator->fails()) {
                $validator->validate();
            } elseif ($this->start_date && $this->end_date) {
                $this->evalPeriodRangeForCalendar();
            }
        }
    }

    public function updatedSelectedRowCheckbox($value)
    {
        $this->selectedRowCheckbox = array_filter($this->selectedRowCheckbox,function ($value) {
            if ($value)
                return $value;
        });
    }

    public function checkIfCheckboxIsSelected($value)
    {
        return array_filter($this->selectedRowCheckbox,function ($row) use ($value) {
            if ($value === $row)
                return true;
            else return false;
        });
    }

    public function evalPeriodRangeForCalendar()
    {
        $start_date = $this->filterByStartDate($this->start_date);
        $end_date = $this->filterByEndDate($this->end_date);
        $date = new DatePeriodsCalculate();
        $data = $date::evalPeriodRangeForCalendar($start_date,$end_date);
        if ($data == 0) {
            $this->outCalendarRange = true;
        } else {
            $this->outCalendarRange = false;
            $this->selectedCalendar = $data;
        }
    }

    public function filterByStartDate($value)
    {
        $start_time = new \DateTime($value);
        $start_time = $start_time->format('d.m.Y 00:00');
        return date_create($start_time);
    }

    public function filterByEndDate($value)
    {
        $end_time = new \DateTime($value);
        $end_time = $end_time->format('d.m.Y 23:59');
        return date_create($end_time);
    }

    public function hasMorePages($value)
    {
        $this->hasMorePage = $value;
//        $this->emitTo('tenant.customer-connections-component','hasMorePages', $value);
    }

    public function getTransactionsProperty()
    {
        $connectionReport = ConnectionReport::withTrashed()
            ->user($this->selectedUser)
            ->device($this->selectedDevice)
            ->contact_type($this->selectedContactType)
            ->status($this->selectedStatus)
            ->tariff($this->selectedTariff)
            ->where(function ($query) {
                if ($this->searchTerm != "") {
                    $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                }
            });
        if (!$this->start_date && !$this->end_date) {
            $connectionReport->date($this->selectedCalendar);
        } elseif ($this->start_date || $this->end_date) {
            $connectionReport->where('start_date', '>=', $this->filterByStartDate($this->start_date))
                ->where('end_date', '<=', $this->filterByEndDate($this->end_date));
        }

        if ($this->customer) {
            $connectionReport->group($this->customer->bdgogid);
        }

        // check for tariff overlapping filter
        if ($this->selectedStatus == 6) {
            $connectionReport = $connectionReport->where('overlaps_tariff',true)->where('is_tariff_overlap_confirmed',false)->get();
        }

        // show live track connections to fill information
        if ($this->selectedStatus == 7) {
            $connectionReport = $connectionReport
//                ->where('userid',auth()->id())
                ->where('device_id',null)
                ->where('contact_id',null)
                ->where('contact_id',null)
                ->where('price',null)
                ->where('tariff_id',null)
                ->where('updated_at',null)
                ->where('billing_state',null)
                ->where('isTV',false)->get();
        }

        return $connectionReport->pluck('id');
    }

    public function filterBySelectedInputs()
    {
        $connAux = Collection::empty();
        $this->connectionsIncome = $this->transactions;
        $perPage = (int)$this->bulk <= 1000 ? (int)$this->bulk : (int)$this->loadMoreNumber;
        if ($this->customer) {
            if (!$this->start_date && !$this->end_date) {
                $connAux = ConnectionReport::withTrashed()
                    ->whereIn('id',$this->connectionsIncome)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate($perPage,['*'],NULL,$this->pageNumber);

                $this->hasMorePages($connAux->hasMorePages());
                return $connAux;
            } elseif ($this->start_date || $this->end_date) {
                $connAux = ConnectionReport::withTrashed()
                    ->whereIn('id',$this->connectionsIncome)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate($perPage,['*'],NULL,$this->pageNumber);

                $this->hasMorePages($connAux->hasMorePages());
                return $connAux;
            } else {
                $connAux = Collection::empty();
                $this->hasMorePages($connAux->count());
                return $connAux;
            }
        } else {
            if (!$this->start_date && !$this->end_date) {
                $connAux = ConnectionReport::withTrashed()
                    ->whereIn('id',$this->connectionsIncome)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate($perPage,['*'],NULL,$this->pageNumber);

                $this->hasMorePages($connAux->hasMorePages());
                return $connAux;
            } elseif ($this->start_date || $this->end_date) {
                $connAux = ConnectionReport::withTrashed()
                    ->whereIn('id',$this->connectionsIncome)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate($perPage,['*'],NULL,$this->pageNumber);

                $this->hasMorePages($connAux->hasMorePages());
                return $connAux;
            } else {
                $connAux = Collection::empty();
                $this->hasMorePages($connAux->count());
                return $connAux;
            }
        }
    }

    public function initCustomers()
    {
        if ($this->customer) {
            $this->selectedCustomer = $this->customer->bdgogid;
        }
        $this->customers = Customer::orderBy('customer_name','asc')->pluck('customer_name','bdgogid');
    }

    public function customersReloads()
    {
        if (!empty($this->selectedCalendar)) {
            $list = null;
            if (!$this->start_date && !$this->end_date)
                $list = ConnectionReport::withTrashed()->date($this->selectedCalendar)->pluck('bdgogid');
            elseif ($this->start_date || $this->end_date)
                $list = ConnectionReport::withTrashed()
                    ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                    ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                    ->pluck('bdgogid');
            $this->customers = Customer::whereIn('bdgogid',$list)->orderBy('customer_name','asc')->pluck('customer_name','bdgogid');
        } else {
            $this->customers = Customer::orderBy('customer_name','asc')->pluck('customer_name','bdgogid');
        }
    }

    public function initUsers()
    {
        $this->users = collect();
        $this->users = $this->customer ? ConnectionReport::withTrashed()->where('bdgogid',$this->customer->bdgogid)->orderBy('username','asc')->pluck('userid','username') : ConnectionReport::withTrashed()->orderBy('username','asc')->pluck('userid','username');
    }

    public function initDevices()
    {
        $this->devices = $this->customer ? Device::where('bdgogid',$this->customer->bdgogid)->orderBy('alias')->pluck('alias','id') : Device::orderBy('alias')->pluck('alias','id');
        $this->devices = $this->devices->map(function ($item) {
            return $this->narrowStringLenght($item);
        });
    }

    public function narrowStringLenght($string): string
    {
        if (strlen($string) > 25) {
            return substr($string,0,25) . '...';
        } else return $string;
    }

    public function initTariffs()
    {
        $this->tariffs = collect();
        $tariffs = Tariff::where('overlap_status',false)->get()->filter(function ($tariff) {
            if ($tariff->global == true)
                return $tariff;
            elseif ($this->customer) {
                if ($this->customer->tariffs()->whereIn('tariff_id',[$tariff->id])->exists()) {
                    return $tariff;
                }
            }
        });
        if (count($tariffs)) {
            $tariffs = $tariffs->toQuery()->orderBy('tariff_name')->get();
            $this->tariffs = $tariffs->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tariff_name' => $this->narrowStringLenght($item->tariff_name)
                ];
            });
        }
        else
            $this->tariffs = Collection::empty();

    }

    public function initDates()
    {
        $date = new DatePeriodsCalculate();
        if (!$this->selectedCalendar) {
            $this->start_date = null;
            $this->end_date = null;
        }
        if ($this->selectedCalendar == 1) {
            $array = $date::getCurrentMonthDatesFormatString();
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        } elseif ($this->selectedCalendar == 2) {
            $array = $date::getLastMonthDatesFormatString();
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        } elseif ($this->selectedCalendar == 3) {
            $array = $date::getMonthsByQuarterFormatString('this');
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        } elseif ($this->selectedCalendar == 4) {
            $array = $date::getMonthsByQuarterFormatString('last');
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        } elseif ($this->selectedCalendar == 5) {
            $array = $date::getCurrentYearDatesFormatString();
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        } elseif ($this->selectedCalendar == 6 ) {
            $array = $date::getLastYearDatesFormatString();
            $this->start_date = $array['start_date'];
            $this->end_date = $array['end_date'];
        }
    }

    public function clearFilters()
    {
        if ($this->printView){
            $this->selectedCalendar = 2;
        }
        else{
            $this->reset([
                'loadMoreNumber', 'selectedCustomer', 'customer', 'selectedUser', 'selectedStatus', 'selectedDevice', 'selectedContactType', 'selectedTariff', 'selectedCalendar'
            ]);
        }

        $this->initDates();
    }

    private function headerConfig()
    {
        if ($this->customer)
            if (auth()->user()->hasRole('User'))
                return [
                    'start_date' => 'Start',
                    'end_date' => 'End',
                    'duration' => 'Duration',
                    'groupname' => 'Customer',
                    'devicename' => 'Device',
                    'username' => 'User',
                    'billing_state' => 'Charge',
                    'notes' => 'Job Description',
                    'actions' => 'Actions'
                ];
            else
                return [
                    'start_date' => 'Start',
                    'end_date' => 'End',
                    'duration' => 'Duration',
                    'units' => 'Units',
                    'price' => 'Price',
                    'groupname' => 'Customer',
                    'devicename' => 'Device',
                    'username' => 'User',
                    'billing_state' => 'Charge',
                    'notes' => 'Job Description',
                    'actions' => 'Actions'
                ];
        else
            if (auth()->user()->hasRole('Admin'))
                return [
                    'start_date' => 'Start',
                    'end_date' => 'End',
                    'duration' => 'Duration',
                    'units' => 'Units',
                    'price' => 'Price',
                    'groupname' => 'Customer',
                    'devicename' => 'Device',
                    'username' => 'User',
                    'billing_state' => 'Charge',
                    'tariff' => 'Tariff',
                    'notes' => 'Job Description',
                    'actions' => 'Actions'
                ];
            else
                return [
                    'start_date' => 'Start',
                    'end_date' => 'End',
                    'duration' => 'Duration',
                    'groupname' => 'Customer',
                    'devicename' => 'Device',
                    'username' => 'User',
                    'billing_state' => 'Charge',
                    'notes' => 'Job Description',
                    'actions' => 'Actions'
                ];
    }

    private function modalHeaderConfig()
    {
        return [
            'username' => 'User',
            'groupname' => 'Customer',
            'devicename' => 'Device',
            'start_date' => 'Start',
            'end_date' => 'End',
            'fee' => 'Fee',
            'billing_state' => 'Bill',
        ];
    }

    public function sort($column)
    {
        if ($column != 'duration' && $column != 'actions' && $column != 'units') {
            $this->sortColumn = $column;
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        }
    }

    public function calculateTotalIncome()
    {
        return number_format($this->retrieveDataToCalculateTotalIncome(),2,',','.');
    }

    public function calculateTotalDuration()
    {
        return $this->retrieveDataToCalculateTotalDuration();
    }

    public function calculateTotalUnits()
    {
        return $this->retrieveDataToCalculateTotalUnits();
    }

    public function retrieveDataToCalculateTotalIncome()
    {
        $sum = 0;
        if ($this->start_date || $this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                    ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->calculatePrice();
                    });
            }
        } elseif (!$this->start_date && !$this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->date($this->selectedCalendar)
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->calculatePrice();
                    });
            }
        }
        return $sum;
    }

    public function retrieveDataToCalculateTotalDuration()
    {
        $sum = 0;
        if ($this->start_date || $this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                    ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->duration();
                    });
            }
        } elseif (!$this->start_date && !$this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->date($this->selectedCalendar)
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->duration();
                    });
            }
        }
        return $sum;
    }

    public function retrieveDataToCalculateTotalUnits()
    {
        $sum = 0;
        if ($this->start_date || $this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                    ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->calculateUnit();
                    });
            }
        } elseif (!$this->start_date && !$this->end_date) {
            if ($this->customer) {
                ConnectionReport::
                group($this->customer->bdgogid)
                    ->user($this->selectedUser)
                    ->device($this->selectedDevice)
                    ->contact_type($this->selectedContactType)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->tariff($this->selectedTariff)
                    ->date($this->selectedCalendar)
                    ->where(function ($query) {
                        if ($this->searchTerm != "") {
                            $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                        }
                    })
                    ->chunk(200, function ($connections) use (&$sum) {
                        foreach ($connections as $connection)
                            $sum += $connection->calculateUnit();
                    });
            }
        }
        return $sum;
    }

    public function calculatePDFIncome($items)
    {
        $sum = 0;
        foreach ($items as $item)
            $sum += $item->calculatePrice();
        return number_format($sum,2,',','.');
    }

    public function calculatePDFDuration($items)
    {
        $sum = 0;
        foreach ($items as $item)
            $sum += $item->duration();
        return $sum;
    }

    public function generatePDF($action)
    {
        $connections = Collection::empty();
        if ($action == 'export') {
            $connections = ConnectionReport::
            group($this->customer->bdgogid)
                ->with('tariff')
                ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                ->whereNotNull('tariff_id')
                ->where(function ($query) {
                    if ($this->searchTerm != "") {
                        $query->whereLike(['username','devicename','notes'],$this->searchTerm ?? '');
                    }
                })
                ->orderBy('start_date','desc')->get();

        } elseif ($action == 'print-view') {
            if ($this->selectedCustomer) {
                $connections = ConnectionReport::
                group($this->customer->bdgogid)
                    ->date($this->selectedCalendar)
                    ->whereNotNull('tariff_id')
                    ->orderBy('start_date','desc')
                    ->get();
            }
        }

        $data = [];

        if (count($connections)) {
            $charged = new Collection();
            $notCharged = new Collection();
            $connections->map(function ($connection) use ($charged,$notCharged) {
                if ($connection->billing_state == 'Bill') {
                    $charged->push($connection);
                }
                elseif ($connection->billing_state == 'DoNotBill') {
                    $notCharged->push($connection);
                }
            });
            $sumChargedDuration = $this->calculatePDFDuration($charged);
            $sumCharged = $this->calculatePDFIncome($charged);
            $sumNotChargedDuration = $this->calculatePDFDuration($notCharged);
            $sumNotCharged = $this->calculatePDFIncome($notCharged);
            $recordedPeriod = date_create($this->start_date)->format('d.m.Y') . ' - ' . date_create($this->end_date)->format('d.m.Y');

            $company = Tenancy::getTenant();
            $data = [
                'company' => $company,
                'period' => $recordedPeriod,
                'customer' => $this->customer ?? null,
                'charged' => $charged,
                'sumChargedDuration' => $sumChargedDuration,
                'sumCharged' => $sumCharged,
                'notCharged' => $notCharged,
                'sumNotChargedDuration' => $sumNotChargedDuration,
                'sumNotCharged' => $sumNotCharged
            ];
        } else $data = [];

        if ($action == 'print-view') {
//            $view = view('tenant.partials.pdf_generate_connections', $data);
//            $html = $view->render();
//            $pdf = PDF::setPaper('a4','landscape')->loadHTML($html);
//            $content = $pdf->download()->getOriginalContent();
//            $delete = Storage::deleteDirectory('public/tenants/' . Tenancy::getTenant()->getTenantKey() .'/pdf');
//            $path = 'public/tenants/' . Tenancy::getTenant()->getTenantKey() .'/pdf/export_pdf-' . now()->format('Y-m-d-H-i-s') .'.pdf';
//            Storage::put($path, $content);
//            $url = Storage::url($path);
//            $pdf->stream();
//            return redirect(url($url));
            $printViewData = [];
            if (!empty($data)) {
                $printCharged = $data['charged'];
                $printCharged = $printCharged->map(function ($item){
                    return $item->id;
                });
                $printNotCharged = $data['notCharged'];
                $printNotCharged = $printNotCharged->map(function ($item){
                    return $item->id;
                });

                $printViewData = [
                    'company' => $data['company']->name,
                    'period' => $data['period'],
                    'customer' => $data['customer']->customer_name,
                    'customerid' => $data['customer']->id,
                    'charged' => $printCharged,
                    'sumChargedDuration' => $data['sumChargedDuration'],
                    'sumCharged' => $data['sumCharged'],
                    'notCharged' => $printNotCharged,
                    'sumNotChargedDuration' => $data['sumNotChargedDuration'],
                    'sumNotCharged' => $data['sumNotCharged']
                ];
            }
            return $printViewData;
        }
        elseif ( $action == 'export') {
            $pdf = PDF::loadView('tenant.partials.pdf_generate_connections', $data)->setPaper('a4', 'landscape')->output();
            return response()->streamDownload(
                fn() => print($pdf), 'export_protocol.pdf'
            );
        }
    }

    public function updatedPrintView($value)
    {
        if ($value) {
            $data = $this->generatePDF('print-view');
            if (!empty($data) && (count($data['charged']) || count($data['notCharged']))) {
                $this->selectedCalendar = 2;
                $this->initDates();
                $this->bulk = 100000000;
            } else {
                $this->reset(['printView', 'bulk']);
                $this->initDates();
            }
        } else {
            if($this->prevSelectedCalendar != 1){
                $this->selectedCalendar = $this->prevSelectedCalendar;
            }
            $this->reset(['bulk']);
            $this->initDates();
        }
    }

    public function startChronos()
    {
        $liveTrack = Livetrack::where('user_id', auth()->id())->first();
        if (empty($liveTrack->id)) {
            $liveTrack = Livetrack::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'bdgo_id' => !empty($this->customer->bdgogid) ? $this->customer->bdgogid : null,
                'start_date' => now()->setTimezone(config('site.default_timezone')),
                'last_poll_date' => now()->setTimezone(config('site.default_timezone'))
            ]);
        }

        $this->counter = !$this->counter;
        $this->emitTo('tenant.timer-nav-component','refresh');
        $this->counterStart = now()->setTimezone(config('site.default_timezone'));
        $data = [
            'counter' => $this->counter,
            'counterStart' => $this->counterStart,
            'customer' => $this->customer ? $this->customer->bdgogid : null
        ];
        session(['counterDataStored' => $data]);
        session(['openTimerComponent' => true]);
//        $this->emitTo('tenant.partials.timer-nav-component','startChronos');
    }

    public function stopChrono_redirected()
    {
        if (session()->has('stopChrono_redirected')) {
            session()->forget('stopChrono_redirected');
            $this->stopChronos();
        }
    }

    public function stopChronos()
    {
        $data = session()->get('counterDataStored');
        session()->forget('counterDataStored');

        if(!empty($data)){
            if (session()->has('openTimerComponent')) {
                session()->forget('openTimerComponent');
                session()->save();
            }

            $this->counter = !$data['counter'];
            $this->counterStart = $data['counterStart'];

            $this->dispatchBrowserEvent('stop');
            $this->updatedSelectedCustomer($data['customer']);
        }
    }

    public function stopChronoValue($value)
    {
        // "00 : 00 : 02 : 44"
        $string = str_replace(" ","",$value);
        $list = explode(":", $string);
        $string = (int)$list[0] > 1 ? '+' . (int)$list[0] . ' hours' : '+' . (int)$list[0] . ' hour' . ' +' . (int)$list[1] . ' minutes' . ' +' . (int)$list[2] . ' seconds';

        $this->counterEnd = new \DateTime(date('d.m.Y H:i:s',strtotime($string,strtotime($this->counterStart))));

        // remove from livetracks records
        $connectionRecoveryService = new ConnectionRecoveryService();
        $connectionRecoveryService->removeLivetracksOnStopChronosByUserId(auth()->id());

        $this->editConnectionChrono();
        $this->counterStart = null;
        $this->counterEnd = null;
        $this->counter = false;
        $this->emitTo('tenant.partials.timer-action-component','$refresh');
    }

    public function editConnectionChrono()
    {
        $data = [
            'counter_start' => $this->counterStart->format('d.m.Y H:i:s'),
            'counter_end' => $this->counterEnd->format('d.m.Y H:i:s'),
            'customer' => $this->customer
        ];
        $this->emit('showModal','tenant.activity-form-component','manual-activity',json_encode($data));
    }

    public function selectAllCheckboxes()
    {
        $this->selectAllCheckboxes = !$this->selectAllCheckboxes;
        if ($this->selectAllCheckboxes) {
            $ids = [];
            $arrayIds = $this->transactions->toArray();
            $collection = ConnectionReport::withTrashed()->whereIn('id',$arrayIds)->chunk(100, function ($collection) {
                foreach ($collection as $item) {
                    if ($item->trashed()) {
                        $this->unSelectedRowCheckbox[$item->id] = $item->id;
                    } else {
                        $this->selectedRowCheckbox[$item->id] = $item->id;
                    }
                }
            });
        } else {
            $this->reInitCheckboxes();
            $this->selectAllCheckboxes = false;
        }
    }

    public function checkboxSelected($value)
    {
        $this->loadMore = true;
        $this->emitTo('tenant.customer-connections-component','checkboxSelected',$value);
    }

    public function reInitCheckboxes()
    {
        $this->selectedRowCheckbox = [];
        $this->unSelectedRowCheckbox = [];
    }

    public function checkboxSelectedAction()
    {
        $selected = ConnectionReport::whereIn('id',$this->selectedRowCheckbox)->get();
        $unSelected = ConnectionReport::withTrashed()->whereIn('id',$this->unSelectedRowCheckbox)->get();
        if (count($unSelected))
            $unSelected->filter(function ($item) {
                $item->restore();
            });

        if (count($selected))
            $selected->filter(function ($item) {
                $item->delete();
            });

        $this->reInitCheckboxes();
        $this->selectAllCheckboxes = false;
//        $this->emitSelf('refresh');
    }

    public function openManualActivityModal($item = null)
    {
        $data = [
            'counter_start' => $this->counterStart ?? null,
            'counter_end' => $this->counterEnd ?? null,
            'connection' => $item ? $item['id'] : null,
            'customer' => $this->customer ? $this->customer->id : null
        ];
        $this->emitTo('tenant.manual-activity-component','addManualActivity',$data);
        $this->dispatchBrowserEvent('openManualActivityModal');
    }

    public function recovery_connection_process()
    {
        if (session()->has('recovery_connection_process')) {
            $this->editConnectionChrono();
        }
    }

    public function openFileUploadModal()
    {
        $this->dispatchBrowserEvent('openFileUploadModal');
    }

    public function borderlineCheck($itemid): bool
    {
        $item = ConnectionReport::withTrashed()->find($itemid);
        $initBorderLimitCheck = new BorderLineConnectionWatcher($item);
        $array = $initBorderLimitCheck::borderlineEmergence();
        if (!empty($array)) {
            return true;
        }
        else return false;
    }

    public function changeBillColumn($modelId)
    {
        $this->loadMoreUpdatePage();
        $model = ConnectionReport::find($modelId);
        if ($model) {
            if ($model->billing_state == 'Bill')
                $model->billing_state = 'DoNotBill';
            else $model->billing_state = 'Bill';
            $model->save();
            $model->refresh();
            $exec = new OverlapsEvaluation($model);
            if ($model->billing_state == 'Bill') {
                if ($model->overlaps_user) {
                    $exec::overlaps_unchecks();
                }
                $exec::overlaps_check();
            } elseif ($model->billing_state == 'DoNotBill') {
                if ($model->overlaps_user) {
                    $exec::overlaps_unchecks();
                }
            }
        }
    }

    public function openOverlapsModal($item)
    {
        $this->emitTo('tenant.connection-overlaps-component','selectedItem',$item);
        $this->dispatchBrowserEvent('openOverlapsModal');
    }

    public function openActivityModal($activityName = 'edit-connection', $item = null)
    {
        $model = ConnectionReport::withTrashed()->whereKey($item)->first();
        $data = [
            'connection' => $item ?? null,
            'customer' => $this->customer ? $this->customer->id : null
        ];
        $this->emitTo('tenant.activity-form-component', 'setMainActivity', $activityName); // done

        if ($model->isTV) {
            $this->emitTo('tenant.activity-form-component','createEditConnection',$data);
            $this->dispatchBrowserEvent('openActivityModal');
        }
        else {
            $this->emitTo('tenant.manual-activity-component','getManualConnection',$data);
            $this->dispatchBrowserEvent('openManualActivityModal');
        }
    }

    public function redirectBorderLineComponent($itemid)
    {
        $connection = ConnectionReport::withTrashed()->find($itemid);
        $initBorderLimitCheck = new BorderLineConnectionWatcher($connection);
        $array = $initBorderLimitCheck::borderlineEmergence();

        if (!empty($array)) {
            return true;
        } else return false;
    }

    public function splittingConnection($itemid)
    {
        $this->emitTo('tenant.splitting-component','setConnection',['connId' => $itemid]);
        $this->dispatchBrowserEvent('openSplittingModal');
    }

    public function dismissTariffConflict($itemid)
    {
        // todo: code for dismiss the tariff conflict.
    }

    public function redirectToastrMessages()
    {
        if ($this->toastrMessageRedirect) {
            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.'.$this->toastrMessageRedirect)]);
            $this->toastrMessageRedirect = null;
        }
    }

    public function openContactModal($value, $activityName = null)
    {
        $this->emitTo('tenant.contact-modal-component','selectedCustomer',$value);
        $this->dispatchBrowserEvent('openContactModal', ['activityName' => $activityName]);
    }

    public function closeContactModal($activityName = null)
    {
        $this->dispatchBrowserEvent('closeContactModal', ['activityName' => $activityName]);
    }

    public function openCustomerModal($activityName = null)
    {
        $this->dispatchBrowserEvent('openCustomerModal', ['activityName' => $activityName]);
    }

    public function closeCustomerModal($activityName = null)
    {
        $this->dispatchBrowserEvent('closeCustomerModal', ['activityName' => $activityName]);
    }

    public function openDeviceModal($value, $activityName = null)
    {
        $this->emitTo('tenant.device-modal-component','setSelectedCustomer',$value);
        $this->dispatchBrowserEvent('openDeviceModal', ['activityName' => $activityName]);
    }

    public function closeDeviceModal($activityName = null)
    {
        $this->dispatchBrowserEvent('closeDeviceModal', ['activityName' => $activityName]);
    }

    public function openUserModal($activityName = null)
    {
        $this->dispatchBrowserEvent('openUserModal', ['activityName' => $activityName]);
    }

    public function closeUserModal($activityName = null)
    {
        $this->dispatchBrowserEvent('closeUserModal', ['activityName' => $activityName]);
    }
    public function loadMoreUpdatePage()
    {
        $this->loadMore = true;
        $this->pageNumber = 2;
    }
}
