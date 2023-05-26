<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\AOHelpers;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Device;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\SharedUserLink\SharedUserLink;
use App\Models\Tenant\Tariff;
use App\Services\ManualActivityTariffApplying;
use App\Services\OverlapsEvaluation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class ManualActivityComponent extends Component
{
    public $selectedConnection;
    public $selectedCustomer;

    public $groups, $users, $devices, $contacts;

    public $bdgogid, $userid, $device_id, $groupname, $username, $devicename, $start_date, $end_date, $tariff_id, $activity_report, $notes, $cont_id;
    public $prevGroupId, $prevUserId, $prevDeviceId, $prevGroupname, $prevUsername, $prevDevicename, $prevStartDate, $prevEndDate, $prevBillingState, $prevTariffId, $prevActivityReport, $prevNotes, $prevContId;
    public $duration, $contact_name;
    public $billing_state = 'Bill';

    public $topic, $start_time, $end_time;
    public $prevTopic, $prevStartTime, $prevEndTime;

    public $contact_type, $prevContactType;
    public $timezone = 'Europe/Berlin';

    public function rules(): array
    {
        return [
            'bdgogid' => ['required'],
            'userid' => ['required'],
            'topic' => ['nullable','string'],
            'device_id' => ['nullable'],
            'billing_state' => ['required'],
            'start_date' => ['required','date_format:d.m.Y','before_or_equal:end_date'],
            'end_date' => ['required','date_format:d.m.Y','after_or_equal:start_date'],
            'start_time' => ['required_with:end_time','date_format:H:i','before_or_equal:end_time'],
            'end_time' => ['required_with:start_time','date_format:H:i','after_or_equal:start_time'],
            'activity_report' => ['nullable'],
            'notes' => ['nullable'],
            'cont_id' => ['nullable'],
            'contact_type' => ['nullable','in:1,2,3,4,5']
        ];
    }

    public function validateData()
    {
        return [
            'bdgogid' => $this->bdgogid,
            'groupname' => $this->groupname,
            'userid' => $this->userid,
            'username' => $this->username,
            'topic' => $this->topic,
            'device_id' => $this->device_id,
            'devicename' => $this->devicename,
            'billing_state' => $this->billing_state,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'activity_report' => $this->activity_report,
            'notes' => $this->notes,
            'cont_id' => $this->cont_id,
            'contact_type' => $this->contact_type
        ];
    }

    protected $listeners = [
        'addManualActivity',
        'customerChange',
        'getManualConnection',
        'closeManualActivityModal',
        'initCustomers',
        'initContacts',
        'initDevices',
        'initUsers',
        'setTimezone',
        'setContactPerson',
        'setDevice',
        'setUser',
    ];

    public function hydrate()
    {
        $this->emit('initManualActivityTimeMasks');
        $this->emit('initDatePickerManualActivity');
    }

    public function mount()
    {
        $this->groups = Customer::orderBy('customer_name','asc')->get();
        $this->groups = $this->groups->pluck('customer_name','bdgogid');
        $this->loadSelectData();
    }

    public function render()
    {
        return view('livewire.tenant.manual-activity-component')
            ->extends('tenant.layouts.contentLayoutMaster');
    }

    public function updated($property)
    {
        $this->validateOnly($property,$this->rules());
    }

    public function updatedUserId($value)
    {
        if ($value) {
            $this->username = SharedUser::where('id',$value)->first();
            $this->username = $this->username->name;
        }
    }

    public function updatedDeviceId($value)  // this method will suffer changes
    {
        if ($value) {
            $connAux = Device::where('id',$value)->first();
            $this->devicename = $connAux->alias;
        } else {
            $this->device_id = null;
            $this->devicename = null;
        }
    }

    public function updatedStartTime($value)
    {
        $validator = Validator::make(
            [
                $this->start_time
            ],
            [
                'required_with:end_time',
                'date_format:H:i',
            ]
        );
        if ($validator->fails()) {
            $validator->validate();
        } else {
            if ($this->end_time) {
                $this->duration = AOHelpers::calculateDuration(Carbon::createFromFormat('H:i',$this->start_time), Carbon::createFromFormat('H:i',$this->end_time));
            }
        }
    }

    public function updatedEndTime($value)
    {
        $validator = Validator::make(
            [
                $this->end_time
            ],
            [
                'required_with:start_time',
                'date_format:H:i',
            ]
        );
        if ($validator->fails()) {

        } else {
            if ($this->start_time) {
                $this->duration = AOHelpers::calculateDuration(Carbon::createFromFormat('H:i',$this->start_time), Carbon::createFromFormat('H:i',$this->end_time));
            }
        }
    }

    public function updatedContId($value)
    {
        if ($value) {
            $this->getContactData($value);
        } else {
            $this->cont_id = null;
            $this->contact_name = null;
        }
    }

    public function contactTypeChange($value)
    {
        if ($this->contact_type == $value) {
            $this->contact_type = null;
        } else {
            $this->contact_type = $value;
        }
    }

    public function getManualConnection($data)
    {
        if (key_exists('connection',$data) && !is_null($data['connection'])) {
            $this->selectedConnection = $data['connection'];

            $model = ConnectionReport::withTrashed()->whereKey($data['connection'])->first();
            $this->bdgogid = $model->bdgogid;
            if ($this->bdgogid) {
                $this->selectedCustomer = Customer::query()->group($this->bdgogid)->first();
                $this->groupname = $this->selectedCustomer ? $this->selectedCustomer->customer_name : "";
                $this->loadSelectData();
            }
            $this->userid = $model->userid;
            $this->device_id = $model->device_id;
            $this->topic = $model->topic;
            $this->billing_state = $model->billing_state;
            $this->start_date = $model->start_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->end_date = $model->end_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->start_time = $model->start_date->setTimezone($this->timezone)->format('H:i');
            $this->end_time = $model->end_date->setTimezone($this->timezone)->format('H:i');
            $this->duration = $model->duration();
            $this->activity_report = $model->activity_report;
            $this->notes = $model->notes;
            $this->cont_id = $model->cont_id;
            $this->contact_type = $model->contact_type;

            $this->prevGroupId = $model->bdgogid;
            $this->prevUserId = $model->userid;
            $this->prevDeviceId = $model->device_id;
            $this->prevTopic = $model->topic;
            $this->prevBillingState = $model->billing_state;
            $this->prevStartDate = $model->start_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->prevEndDate = $model->end_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->prevStartTime = $model->start_date->setTimezone($this->timezone)->format('H:i');
            $this->prevEndTime = $model->end_date->setTimezone($this->timezone)->format('H:i');
            $this->duration = $model->duration();
            $this->prevActivityReport = $model->activity_report;
            $this->prevNotes = $model->notes;
            $this->prevContId = $model->cont_id;
            $this->prevContactType = $model->contact_type;
        }
    }

    public function getContactData($item_id)
    {
        $this->cont_id = $item_id;
        $model = Contact::query()->whereKey($this->cont_id)->first();
        $this->contact_name = $model->firstname . ' ' . $model->lastname;
    }

    public function addManualActivity($data)
    {
        if (session()->has('recovery_connection_process')) {
            $recoveries = auth()->user()->connection_recovery()->get();
            $recoveries->map(function ($item) {
                $item->forceDelete();
            });
            session()->forget('recovery_connection_process');
        }

        if (key_exists('counter_start',$data) && key_exists('counter_end',$data)) {
            $this->start_date = !empty($this->date_input($data['counter_start'])) ? $this->date_input($data['counter_start']) : now()->setTimezone($this->timezone)->format('d.m.Y');
            $this->end_date = !empty($this->date_input($data['counter_end'])) ? $this->date_input($data['counter_end']) : now()->setTimezone($this->timezone)->format('d.m.Y');
            $this->start_time = $data['counter_start'] ? date('H:i',strtotime($data['counter_start'])) : null;
            $this->end_time = $data['counter_end'] ? date('H:i',strtotime($data['counter_end'])) : null;
            $this->duration = ($data['counter_start'] && $data['counter_end'])
                ? AOHelpers::calculateDuration(Carbon::createFromFormat('d.m.Y H:i:s',$data['counter_start']), Carbon::createFromFormat('d.m.Y H:i:s',$data['counter_end']))
                : null;
        }
        if (key_exists('customer',$data)) {
            if ($data['customer']) {
                $this->selectedCustomer = Customer::where('id', $data['customer'])->first();
                if ($this->selectedCustomer) {
                    $this->bdgogid = $this->selectedCustomer ? $this->selectedCustomer->bdgogid ?? "" : "";
                    $this->groupname = $this->selectedCustomer->customer_name;
                }
            }
        }
        $this->loadSelectData();
        $this->userid = auth()->id();
        if ($this->userid) {
            $user = SharedUser::query()->whereKey($this->userid)->first();
            $this->username = $user->name;
        }
    }

    public function time_input($time)
    {
        $list = explode(" ",$time);
        return $list[1];
    }

    public function date_input($date)
    {
        $list = explode(" ",$date);
        return $list[0];
    }

    public function loadSelectData()
    {
        $this->initUsers();
        $this->initDevices();
        $this->initDate();
        $this->initContacts();
        $this->setTimezone();
    }

    public function initCustomers()
    {
        $this->groups = Customer::orderBy('customer_name','asc')->get();
        $this->groups = $this->groups->pluck('customer_name','bdgogid');
    }

    public function initUsers()
    {
        $this->users = collect();
        $pluckOnLink = SharedUserLink::all()->pluck('user_id');
        $this->users = SharedUser::query()->active(true)->whereNotIn('id',$pluckOnLink)->orderBy('name')->pluck('id','name');
    }

    public function setTimezone($timezone = null)
    {
        if ($timezone) {
            $this->timezone = $timezone;
        } else {
            $this->dispatchBrowserEvent('setTimezone');
        }
    }

    public function initDevices()
    {
        $this->devices = $this->selectedCustomer ? Device::query()->where('bdgogid',$this->selectedCustomer->bdgogid)->pluck('alias','id') : Device::all()->pluck('alias','id');
    }

    public function initDate()
    {
        $this->start_date = now()->format('d.m.Y');
        $this->end_date = now()->format('d.m.Y');
    }

    public function initContacts()
    {
        $this->contacts = $this->selectedCustomer ? $this->selectedCustomer->contacts()->orderBy('firstname')->get() : Collection::empty();
    }

    public function generateRandomOwnAppId(): string
    {
        return 'tenant' . Tenancy::getTenant()->getTenantKey() . '-' .
            strtolower($this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4)
                . '-' . $this->generateRandomString(12));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
    }

    public function save()
    {
        $validation = Validator::make($this->validateData(),$this->rules());
        if ($validation->fails()) {
            $error = $validation->getMessageBag();
            $this->dispatchBrowserEvent('focusErrorInput', ['field' => array_key_first($error->getMessages())]);
            $validation->validate();
        } else {
            if ($this->selectedConnection) {
                $overlappedLoads = [];
                $overlappedCheck = false;
                $data = [];
                $validateData = [];
                if ($this->bdgogid != $this->prevGroupId) {
                    $group = Customer::query()->where('bdgogid',$this->bdgogid)->first();
                    $data = array_merge($data, [
                        'bdgogid' => $this->bdgogid,
                        'groupname' => $group->customer_name
                    ]);
                    $validateData = array_merge($validateData, [
                        'bdgogid' => ['required'],
                        'groupname' => ['string']
                    ]);
                    $overlappedCheck = true;
                }
                if ($this->userid != $this->prevUserId) {
                    $user = SharedUser::query()->whereKey($this->userid)->first();
                    $data = array_merge($data, [
                        'userid' => $this->userid,
                        'username' => $user->name
                    ]);
                    $validateData = array_merge($validateData, [
                        'userid' => ['required'],
                        'username' => ['string']
                    ]);
                    $overlappedCheck = true;
                }
                if ($this->device_id != $this->prevDeviceId) {
                    $device = Device::query()->whereKey($this->device_id)->first();
                    $data = array_merge($data, [
                        'device_id' => $this->device_id,
                        'devicename' => $device->name
                    ]);
                    $validateData = array_merge($validateData, [
                        'device_id' => ['required'],
                        'devicename' => ['string']
                    ]);
                    $overlappedCheck = true;
                }
                if ($this->topic != $this->prevTopic) {
                    $data = array_merge($data, ['topic' => $this->topic]);
                    $validateData = array_merge($validateData, [
                        'topic' => ['nullable','string']
                    ]);
                }
                if ($this->billing_state != $this->prevBillingState) {
                    $model = ConnectionReport::withTrashed()->where('id',$this->selectedConnection)->first();
                    $model->update([
                        $this->billing_state => $this->billing_state
                    ]);
                    if ($this->prevBillingState == 'Hide') {
                        $model->restore();
                    }
                    if ($model->billing_state == 'Hide') {
                        $model->delete();
                    }
                    $overlappedLoads['billing_state'] = $this->billing_state;
                    $overlappedCheck = true;
                }
                if ((Carbon::createFromFormat('d.m.Y',$this->start_date,$this->timezone)->format('d.m.Y') != $this->prevStartDate) || (Carbon::createFromFormat('H:i',$this->start_time,$this->timezone)->format('H:i') != $this->prevStartTime)) {
                    $data = array_merge($data, ['start_date' => Carbon::createFromFormat('d.m.Y H:i',$this->start_date . " " . $this->start_time,$this->timezone)->setTimezone('UTC')->format('d.m.Y H:i')]);
                    $validateData = array_merge($validateData, [
                        'start_date' => ['date_format:d.m.Y H:i']
                    ]);
                    $overlappedCheck = true;
                }
                if ((Carbon::createFromFormat('d.m.Y',$this->end_date,$this->timezone)->format('d.m.Y') != $this->prevEndDate) || (Carbon::createFromFormat('H:i',$this->end_time,$this->timezone)->format('H:i') != $this->prevEndTime)) {
                    $data = array_merge($data, ['end_date' => Carbon::createFromFormat('d.m.Y H:i',$this->end_date . " " . $this->end_time,$this->timezone)->setTimezone('UTC')->format('d.m.Y H:i')]);
                    $validateData = array_merge($validateData, [
                        'end_date' => ['date_format:d.m.Y H:i']
                    ]);
                    $overlappedCheck = true;
                }
                if ($this->notes != $this->prevNotes) {
                    $data = array_merge($data, ['notes' => $this->notes]);
                    $validateData = array_merge($validateData, [
                        'notes' => ['nullable']
                    ]);
                }
                if ($this->activity_report != $this->prevActivityReport) {
                    $data = array_merge($data, ['activity_report' => $this->activity_report]);
                    $validateData = array_merge($validateData, [
                        'activity_report' => ['nullable']
                    ]);
                }
                if ($this->cont_id != $this->prevContId) {
                    $data = array_merge($data, ['cont_id' => $this->cont_id]);
                    $validateData = array_merge($validateData, [
                        'cont_id' => ['nullable']
                    ]);
                }
                if ($this->contact_type != $this->prevContactType) {
                    $data = array_merge($data, ['contact_type' => $this->contact_type]);
                    $validateData = array_merge($validateData, [
                        'contact_type' => ['nullable','in:1,2,3,4,5']
                    ]);
                }
                $validator = Validator::make($data,$validateData)->validate();
                if (count($validator)) {
                    $connection = ConnectionReport::where('id', $this->selectedConnection)->first();
                    $connection->update($validator);
                    $service = new ManualActivityTariffApplying($connection,['bdgogid' => $this->bdgogid]);
                    $service::applyTariffToConnection();
                    if ($overlappedCheck) { // check for overlapping or not.
                        $connection->refresh();
                        $exec = new OverlapsEvaluation($connection);
                        if ($connection->overlaps_user && key_exists('billing_state',$overlappedLoads) && ($overlappedLoads['billing_state'] == 'DoNotBill' || $overlappedLoads['billing_state'] == 'Hide')) {
                            $exec::overlaps_unchecks();
                        } else {
                            if ($connection->overlaps_user) {
                                $exec::overlaps_unchecks();
                            }
                            $exec::overlaps_check();
                        }
                    }
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Connection Updated!')]);
                }
            }
            else {
                $connection = ConnectionReport::create([
                    'id' => $this->generateRandomOwnAppId(),
                    'bdgogid' => $this->bdgogid,
                    'groupname' => $this->groupname,
                    'userid' => $this->userid,
                    'username' => $this->username,
                    'topic' => $this->topic,
                    'device_id' => $this->device_id,
                    'devicename' => $this->devicename,
                    'support_session_type' => 1,
                    'billing_state' => $this->billing_state,
                    'start_date' => Carbon::createFromFormat('d.m.Y H:i',$this->start_date . " " . $this->start_time,$this->timezone)->setTimezone('UTC'),
                    'end_date' => Carbon::createFromFormat('d.m.Y H:i',$this->end_date . " " . $this->end_time,$this->timezone)->setTimezone('UTC'),
                    'notes' => $this->notes,
                    'activity_report' => $this->activity_report,
                    'isTV' => false,
                    'cont_id' => $this->cont_id,
                    'contact_type' => $this->contact_type
                ]);
                $service = new ManualActivityTariffApplying($connection,['bdgogid' => $this->bdgogid]);
                $service::applyTariffToConnection();
                $exec = new OverlapsEvaluation($connection);
                $exec::overlaps_check();
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Connection Created!')]);
            }
            $this->emitTo('tenant.customer-connections-component','refresh');
        }
        $this->closeManualActivityModal();
    }

    public function closeManualActivityModal()
    {
        $this->clearVariables();
        $this->dispatchBrowserEvent('closeManualActivityModal');
    }

    public function clearVariables()
    {
        $this->selectedConnection = null;
        $this->selectedCustomer = null;
        $this->contact_name = null;

        $this->bdgogid = null;
        $this->userid = null;
        $this->device_id = null;
        $this->groupname = null;
        $this->username = null;
        $this->devicename = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->reset(['billing_state']);
        $this->tariff_id = null;
        $this->activity_report = null;
        $this->notes = null;
        $this->cont_id = null;
        $this->contact_type = null;

        $this->topic = null;
        $this->start_time = null;
        $this->end_time = null;

        $this->prevGroupId = null;
        $this->prevUserId = null;
        $this->prevDeviceId = null;
        $this->prevGroupname = null;
        $this->prevUsername = null;
        $this->prevDevicename = null;
        $this->prevStartDate = null;
        $this->prevEndDate = null;
        $this->prevBillingState = null;
        $this->prevTariffId = null;
        $this->prevActivityReport = null;
        $this->prevNotes = null;
        $this->prevContId = null;
        $this->prevContactType = null;

        $this->resetValidation();
    }

    public function setUser($user_id)
    {
        $this->userid = $user_id;
    }

    public function setDevice($device_id)
    {
        $this->device_id = $device_id;
    }

    public function setContactPerson($contact_id)
    {
        $this->cont_id = $contact_id;
    }

    public function showContactModal()
    {
        $this->emitTo('tenant.customer-connections-component', 'shownContactModal', $this->selectedCustomer);
    }

    public function showDeviceModal()
    {
        $this->emitTo('tenant.customer-connections-component','shownDeviceModal',$this->selectedCustomer);
    }

    public function openUserModal()
    {
        $this->emitTo('tenant.customer-connections-component','shownUserModal');
    }

    public function openCustomerModal()
    {
        $this->emitTo('tenant.customer-modal-component','shownCustomerModal');
    }
}
