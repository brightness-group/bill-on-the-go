<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\CoreHelpers;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Tariff;
use App\Services\CalculateCustomerOperatingTime;
use App\Services\RetrieveDataFromAPI;
use App\Services\TariffApplyingResolve;
use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Tenancy\Facades\Tenancy;
use DB;

class CustomerComponent extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';
    public int $perPage = 20;

    public bool $connected = false;
    public $access_token = null;
    public $refreshToken = null;
    public bool $alertExpireToken = false;
    public bool $access_token_expire = false;

    public bool $isOpenActiveEditable = false;
    public $activeEditableField = null;

    public $function_users, $function_groups, $function_connections;

    public string $search = '';

    public $action, $selectedItem;

    public $flashMessage;
    public string $toastrMessage = '';

    public int $selectedStatus = 1;

    public bool $selectedAll = false;
    public array $selectedRowArchieveActive = [];
    public $collection;

    public $notification;
    public string $sortColumn = 'customer_name';
    public string $sortDirection = 'asc';
    public array $headers = [
        'customer_name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'city' => 'City',
        'planned_operating_time' => 'Planned Operating Time',
        'curr_month_actual_operate_time' => 'Current Month',
        'last_month_actual_operate_time' => 'Last Month',
        'last_quarter_actual_operate_time' => 'Last Quarter',
        'current_year_actual_operate_time' => 'Current Year',
        'last_year_actual_operate_time' => 'Last Year'
    ];
    public array $typeCastFields = [
        'curr_month_actual_operate_time',
        'current_year_actual_operate_time',
        'last_month_actual_operate_time',
        'last_quarter_actual_operate_time',
        'last_year_actual_operate_time'
    ];
    public bool $enableOperatingTimeButton = false;

    protected $listeners = [
        'refreshCustomerGroupAndConnectionComponent' => '$refresh',
        'refreshComponent',
        'refreshParent' => '$refresh',
        'searchUpdate',
        'showToastrMessageRedirected',
        'clearSelectedItem',
        'retrieveFromAPIEmittedEvent' => 'retrieveFromAPI',
        'showEditableField',
        'sort',
    ];

    public function mount()
    {
        $this->selectedStatus = CoreHelpers::getPreviousState('customer', 'selectedStatus', $this->selectedStatus);
        $this->sortColumn = CoreHelpers::getPreviousState('customer', 'sortColumn', $this->sortColumn);
        $this->sortDirection = CoreHelpers::getPreviousState('customer', 'sortDirection', $this->sortDirection);

        $unrelated_contacts_check = auth()->user()->contact_user_session()->get();
        if (count($unrelated_contacts_check)) {
            $unrelated_contacts_check->map(function ($contact_user_session) {
                Contact::query()->find($contact_user_session->contact_id)->delete();
            });
        }
        if (session()->has('toastrMessage')) {
            $this->toastrMessage = session()->get('toastrMessage');
            session()->forget('toastrMessage');
        } elseif (session()->has('anydesk_callback')) {
            session()->forget('anydesk_callback');
            $this->toastrMessage = 'access_granted';
        } elseif (session()->has('anydesk_callback_fails')) {
            session()->forget('anydesk_callback_fails');
            $this->toastrMessage = 'access_granted_fails';
        }
        if (session()->has('customerCreated')) {
            $this->customerCreated(session()->get('customerCreated'));
        } else {
            $this->reloadTariffsCollections();
        }

        $this->notification = auth()->user()->customer_data_notification;
        $this->access_token = Tenancy::getTenant()->anydesk_access_token;
        $this->refreshToken = Tenancy::getTenant()->anydesk_refresh_token;
        if ($this->access_token)
            $this->access_token_expire = (Tenancy::getTenant()->anydesk_access_token_for_expire_check)->diffInHours(now()) >= 24;
        if ($this->access_token && !$this->access_token_expire) {
            $this->connected = true;
            $this->reset(['alertExpireToken']);
        } elseif ($this->access_token_expire) {
            $this->alertExpireToken = true;
            $this->reset(['connected','access_token']);
        }
        else {
            $this->reset(['connected','access_token','refreshToken', 'access_token_expire','alertExpireToken']);
        }

        // Check get request for column sorting.
        $sort = request()->get('sort', null);
        if (!empty($sort) && in_array($sort, array_keys($this->headers))) {
            $direction = request()->get('direction', null);
            $direction = (in_array($direction, ['asc', 'desc'])) ? $direction : $this->sortDirection;

            $this->sort($sort, $direction);
        }

        // Check and append search values.
        $search = request()->get('search', null);
        if (!empty($search)) {
            $this->search = $search;
        }
    }

    public function calculateActualOperatingTime(): void
    {
        if($this->enableOperatingTimeButton == false){
            $this->enableOperatingTimeButton = true;
            $calculateCustomerOperatingTimeService = new CalculateCustomerOperatingTime();
            $calculateCustomerOperatingTimeService->calculateActualOperatingTime();
            $this->enableOperatingTimeButton = false;
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Updated')]);

        }
    }

    public function getQueryString()
    {
        return [];
    }

    public function render()
    {
        $container = 'container';

        return view('livewire.tenant.customer-component', [
            'customers' => $this->selectedInputCollection()
        ])
            ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
            ->section('content');
    }

    public function updated()
    {
//        $this->resetPage();
    }

    public function selectedInputCollection()
    {
        if ($this->selectedStatus == 1) {
            $query = Customer::query();

            if ($this->search) {
                if (in_array($this->sortColumn, $this->typeCastFields)) {
                    $query->orderBy(DB::raw("ABS({$this->sortColumn})"), $this->sortDirection);
                } else {
                    $query->orderBy($this->sortColumn, $this->sortDirection);
                }

                return $query->withTrashed()
                    ->where('active', true)
                    ->where('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('address', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%')
                    ->paginate($this->perPage);
            } else {
                if (in_array($this->sortColumn, $this->typeCastFields)) {
                    $query->orderBy(DB::raw("ABS({$this->sortColumn})"), $this->sortDirection);
                } else {
                    $query->orderBy($this->sortColumn, $this->sortDirection);
                }

                return $query->withTrashed()
                    ->where('active', true)
                    ->paginate($this->perPage);
            }
        } elseif ($this->selectedStatus == 2) {
            $query = Customer::query();

            if ($this->search) {
                if (in_array($this->sortColumn, $this->typeCastFields)) {
                    $query->orderBy(DB::raw("ABS({$this->sortColumn})"), $this->sortDirection);
                } else {
                    $query->orderBy($this->sortColumn, $this->sortDirection);
                }

                return $query->withTrashed()
                    ->where('active', false)
                    ->where('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('address', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%')
                    ->paginate($this->perPage);
            } else {
                if (in_array($this->sortColumn, $this->typeCastFields)) {
                    $query->orderBy(DB::raw("ABS({$this->sortColumn})"), $this->sortDirection);
                } else {
                    $query->orderBy($this->sortColumn, $this->sortDirection);
                }

                return $query->withTrashed()
                    ->where('active', false)
                    ->paginate($this->perPage);
            }
        }
    }

    public function updatedSelectedStatus()
    {
        $this->selectedRowArchieveActive = [];
        $this->selectedAll = false;
        CoreHelpers::setState('customer', 'selectedStatus', $this->selectedStatus);
        $this->resetPage();
    }

    public function updatedSelectedAll($value)
    {
        if ($value) {
            $collection = $this->selectedInputCollection()->items();
            $ids = [];
            foreach ($collection as $item)
                $ids[$item->id] = (string)$item->id;
            $this->selectedRowArchieveActive = array_combine($ids,$ids);
        }
        else $this->selectedRowArchieveActive = [];
    }

    public function updatedSelectedRowArchieveActive($value)
    {
        $this->selectedRowArchieveActive = array_filter($this->selectedRowArchieveActive, function ($value) {
            if ($value)
                return $value;
        });
        if (count($this->selectedRowArchieveActive) == count($this->selectedInputCollection()->items())) {
            $this->selectedAll = true;
        } else $this->selectedAll = false;
    }

    public function selectedRowActions()
    {
        if ($this->selectedStatus == 1) {
            Customer::withTrashed()->whereIn('id', $this->selectedRowArchieveActive)->update(['active' => false]);
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Deactivated!')]);
        } elseif ($this->selectedStatus == 2) {
            Customer::withTrashed()->whereIn('id', $this->selectedRowArchieveActive)->update(['active' => true]);
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Activated!')]);
        }
        $this->resetPage();
        if ($this->selectedStatus == 2 && !count($this->selectedInputCollection()->items()))
            $this->selectedStatus = 1;
        $this->selectedRowArchieveActive = [];
        $this->selectedAll = false;
    }

    public function updatedNotification($value)
    {
        auth()->user()->customer_data_notification = $value;
        auth()->user()->save();
    }

    public function markAsIncompleteData($id):bool
    {
        $customer = Customer::find($id);
        if (empty($customer->email) || empty($customer->address) || empty($customer->phone) || empty($customer->country) ||
            empty($customer->city) || empty($customer->post_code) || empty($customer->comment) || empty($customer->website)) {
            return true;
        } else {
            return false;
        }
    }

    public function incompleteDataFilled($customer)
    {
        $array = [];

        if (empty($customer->email))
            $array = array_merge($array,['email' => __('locale.Email')]);
        if (empty($customer->address))
            $array = array_merge($array,['address' => __('locale.Address')]);
        if (empty($customer->phone))
            $array = array_merge($array,['phone' => __('locale.Phone')]);
        if (empty($customer->country))
            $array = array_merge($array,['country' => __('locale.Country')]);
        if (empty($customer->city))
            $array = array_merge($array,['city' => __('locale.City')]);
        if (empty($customer->post_code))
            $array = array_merge($array,['zip' => __('locale.ZIP')]);
        if (empty($customer->comment))
            $array = array_merge($array,['comment' => __('locale.Comments')]);
        if (empty($customer->website))
            $array = array_merge($array,['website' => __('locale.Website')]);

        $string = '';
        if ($count = count($array)) {
            foreach ($array as $key => $value) {
                if ($count > 1)
                    $string .= $value . ' | ';
                else
                    $string .= $value;
                $count--;
            }
        }
        return $string;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function searchUpdate($search)
    {
        $this->search = $search;

        $this->dispatchBrowserEvent('searchUpdate', ['search' => $this->search]);
    }

    public function showToastrMessageRedirected()
    {
        if ($this->toastrMessage == 'created') {
            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.Customer Created!')]);
        }
        elseif ($this->toastrMessage == 'updated') {
            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.Customer Updated!')]);
        }
        elseif ($this->toastrMessage == 'access_granted') {
            session(['access_granted' => true]);
            $this->emitSelf('retrieveFromAPIEmittedEvent');
//            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Access Granted').'!']);
        }
        elseif ($this->toastrMessage == 'access_granted_fails') {
            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Access Granted Failed').'!']);
        }
        $this->reset(['toastrMessage']);
    }

    public function customerCreated($value)
    {
        $customer = Customer::find($value);
        $tariffs = Tariff::where('overlap_status',false)->where('attempt',true)->get();
        if (count($tariffs)) {
            $tariffs->toQuery()->update(['attempt' => false]);
            $customer->tariffs()->attach($tariffs->pluck('id')->toArray());
            foreach ($tariffs as $tariff) {
                if ($tariff->bdgogid) {
                    $apply = new TariffApplyingResolve($tariff->id,['permanent'=>$tariff->permanent,'bdgogid'=>$customer->bdgogid]);
                    $apply::applyTariffToConnections();
                }
            }
        }
        $tariffs = Tariff::withTrashed()->where('overlap_status',false)->where('attempt','=',true)->get();
        if (count($tariffs)) {
            $tariffs->toQuery()->update(['attempt' => false]);
            $customer->tariffs()->attach($tariffs->pluck('id')->toArray());
        }
        session()->forget('customerCreated');
    }

    public function reloadTariffsCollections()
    {
        $tariffs = Tariff::where('overlap_status',false)->where('attempt','=',true)->get();
        if (count($tariffs))
            $tariffs->toQuery()->forceDelete();
        $tariffs = Tariff::withTrashed()->where('overlap_status',false)->where('attempt','=',true)->get();
        if (count($tariffs))
            $tariffs->toQuery()->forceDelete();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'delete') {
            $this->dispatchBrowserEvent('openCustomerDeleteModal');
        }
        elseif ($action == 'archieve') {
            $this->dispatchBrowserEvent('openCustomerArchievedModal');
        }
        elseif ($action == 'activate') {
            $this->dispatchBrowserEvent('openCustomerActivateModal');
        }
    }

    public function clearSelectedItem()
    {
        $this->selectedItem = '';
        $this->search = '';
    }

    public function archieved()
    {
        $customer = Customer::find($this->selectedItem);
        $customer->delete();
        $this->closeCustomerArchievedModal();
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Archieved!')]);
    }

    public function activate()
    {
        $customer = Customer::withTrashed()->find($this->selectedItem);
        $customer->restore();
        $this->closeCustomerActivateModal();
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Activated!')]);
    }

    public function destroy()
    {
        $customer = Customer::withoutTrashed()->find($this->selectedItem);
        if (is_null($customer))
            $customer = Customer::withTrashed()->find($this->selectedItem);

        $this->checkTariffsRelatedForDelete($customer);
        $customer->forceDelete();
        $this->resetPage();
        $this->closeCustomerDeleteModal();
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Customer Deleted!')]);
    }

    public function checkTariffsRelatedForDelete(Customer $customer)
    {
        $tariffs_active = $customer->tariffs()->get();
        if (count($tariffs_active)) {
            $customer->tariffs()->detach($tariffs_active->pluck('id'));
            $tariffs_active->toQuery()->forceDelete();
        }
        $tariffs_archieved = $customer->tariffs()->withTrashed()->get();
        if (count($tariffs_archieved)) {
            $customer->tariffs()->detach($tariffs_archieved->pluck('id'));
            $tariffs_archieved->toQuery()->forceDelete();
        }
    }

    public function closeCustomerArchievedModal()
    {
        $this->dispatchBrowserEvent('closeCustomerArchievedModal');
        $this->clearSelectedItem();
    }

    public function closeCustomerActivateModal()
    {
        $this->dispatchBrowserEvent('closeCustomerActivateModal');
        $this->clearSelectedItem();
    }

    public function closeCustomerDeleteModal()
    {
        $this->dispatchBrowserEvent('closeCustomerDeleteModal');
        $this->clearSelectedItem();
    }

    // Teamviewer Retrieve Data

    public function retrieveFromAPI()
    {
        $data = [];
        if ($this->ping()) {
            $groups = $this->getGroupsAPI();
            $devices = $this->getDevicesAPI();
            $connections = $this->getConnectionsAPI();
            if (!$groups || !$devices || !$connections) {
                $data = [
                    'type' => 'toastrTVRetrieveError',
                    'message' => __('locale.Something is wrong with your request. Please contact your administrator!')
                ];
//                $this->dispatchBrowserEvent('toastrTVRetrieveError', ['message' => __('locale.Something is wrong with your request. Please contact your administrator!')]);
            } else {
                $flag = session()->has('access_granted');
                $data = [
                    'type' => 'toastrTVRetrieveSuccess',
                    'message' => $flag ? __('locale.Access Granted') : __('locale.Updated')
                ];
                if ($flag)
                    session()->forget('access_granted');
//                $this->dispatchBrowserEvent('toastrTVRetrieveSuccess',['message' => __('locale.Updated')]);
                $this->emitTo('tenant.customer-connections-component','retrievedDataFromAPITeamViewer');
            }
        }
        else {
            $data = [
                'type' => 'toastrTVRetrieveError',
                'message' => __('locale.The access token you provide isn\'t valid.')
            ];
//            $this->dispatchBrowserEvent('toastrTVRetrieveError', ['message' => __('locale.The access token you provide isn\'t valid.')]);
        }
        $this->dispatchBrowserEvent($data['type'], ['message' => $data['message']]);
    }

    // check if API is available
    public function ping(): bool
    {
        return RetrieveDataFromAPI::ping($this->access_token);
    }

    // retrieve Groups
    public function getGroupsAPI():bool
    {
        return RetrieveDataFromAPI::groups($this->access_token);
    }

    // retrieve Devices
    public function getDevicesAPI(): bool
    {
        return RetrieveDataFromAPI::devices($this->access_token);
    }

    // retrieve Connections
    public function getConnectionsAPI(): bool
    {
        return RetrieveDataFromAPI::connections($this->access_token);
    }

    // show editable field
    public function showEditableField($name, $key, $value)
    {
        $this->emitTo('tenant.editable-component', 'setSelectedValue', $name, $key, $value);

        $this->isOpenActiveEditable = true;
    }

    public function openCustomersSummarizeModal()
    {
        $this->emit('showModal', 'tenant.summarize-customers-component',json_encode(['selected_customers' => $this->selectedRowArchieveActive]));
    }

    public function refreshComponent()
    {
        $this->selectedRowArchieveActive = [];
        $this->resetPage();
    }

    public function sort($key, $dir = null)
    {
        $sortDirection = $this->sortColumn == $key && $this->sortDirection == 'asc' ? 'desc' : 'asc';
        if($dir)
            $sortDirection = $dir;
        $this->sortDirection = $sortDirection;
        $this->sortColumn = $key;

        CoreHelpers::setMultipleState('customer', [
            'sortColumn' => $this->sortColumn,
            'sortDirection' => $this->sortDirection,
        ]);
    }
}
