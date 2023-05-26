<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\AOHelpers;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Device;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\SharedUserLink\SharedUserLink;
use App\Models\Tenant\Tariff;
use App\Models\Tenant\Contact;
use App\Services\ManualActivityTariffApplying;
use App\Services\OverlapsEvaluation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class ActivityFormComponent extends Component
{
    /* common properties */
    public bool $isEdit = false;
    public string $mainActivityName = 'edit-connection';
    public $duration, $contact_name;
    public $existingStateData;

    /* 1. Edit connection properties */
    public $selectedConnection;
    public $selectedCustomer;

    public $isEditMode = false;
    public $status = '';
    public $devices = [];

    public $groups, $users, $tariffs, $contacts;

    public $globalTariffs, $customTariffs;

    public $bdgogid, $userid, $cont_id, $device_id, $groupname, $username, $devicename, $start_date, $end_date, $billing_state = 'Bill', $tariff_id, $activity_report, $notes;
    public $support_session_type = 1;
    public $prevGroupId, $prevUserId, $prevDeviceId, $prevGroupname, $prevUsername, $prevDevicename, $prevStartDate, $prevEndDate, $prevBillingState, $prevTariffId, $prevActivityReport, $prevNotes, $prevContId, $prevStatus;

    public $isVisible = false;

    public $toastrMessageRedirect;

    /* 2. Manual Activity properties */
    public $topic, $start_time, $end_time;
    public $prevTopic, $prevStartTime, $prevEndTime;
    public $contact_type, $prevContactType;
    public $timezone = 'Europe/Berlin';

    protected $listeners = [

        /* Common listeners */
        'setMainActivity',
        'initUsers',
        'initDevices',
        'setPrevDataConnection',

        /* Edit connection listeners */
        'createEditConnection',
        'closedActivityModal',
        'initCustomers',
        'initContacts',
        'setContactPerson',
        'setDevice',
        'setUser',
        'updatedGroupId',
        'updatedDeviceId',
        'updatedUserId',

        /* Edit Manual Activity listeners */
        'addManualActivity',
        'customerChange',
        'initSelect2',
        /*
        'getManualConnection',
        'closeManualActivityModal',
        'initCustomers',
        'initContacts',
        'initDevices',
        'setTimezone',
        'setContactPerson',
        'setDevice',
        'setUser',*/

    ];

    public function hydrate($event)
    {
        if ($this->mainActivityName == 'manual-activity') {
            $this->emit('initManualActivityTimeMasks');
            $this->emit('initDatePickerManualActivity');
        }

        if ($this->mainActivityName == 'manual-activity') {

            // set device
            if ($this->device_id == null && $this->selectedConnection) {
                $this->device_id = $this->selectedConnection->device_id;
                if (!$this->devicename && $this->device_id) {
                    $this->devicename = Device::find($this->device_id)->alias;
                }
            }
            // set tariff
            if ($this->tariff_id == null && $this->selectedConnection) {
                $this->tariff_id = $this->selectedConnection->tariff_id;
            }
            $this->dispatchBrowserEvent('setDateFlatPicker', ['start_date' => $this->start_date, 'end_date' => $this->end_date]);

        } else {
            $this->dispatchBrowserEvent('load_flatpickrs', ['start' => $this->start_date, 'end' => $this->end_date]);
        }
        $this->setHourMinutePicker();
        $this->dispatchBrowserEvent('initSelect2');
    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('initTopicCharVal');
    }

    public function mount($activityName, $inputs, $prevInputData = null)
    {
        $this->timezone = config('site.default_timezone');

        $inputs = json_decode($inputs, true);
        $this->initDevices();
        $data = [
            'connection' => !empty($inputs['item']) ? $inputs['item'] : null,
            'customer' => !empty($inputs['customer']) ? $inputs['customer'] : null,
            'counter_start' => !empty($inputs['counter_start']) ? $inputs['counter_start'] : null,
            'counter_end' => !empty($inputs['counter_end']) ? $inputs['counter_end'] : null,
        ];

        $this->setMainActivity($activityName);

        if ($this->mainActivityName == 'edit-connection') {
            $this->createEditConnection($data);
            $this->groups = Customer::orderBy('customer_name', 'asc')->get();
            $this->groups = $this->groups->pluck('customer_name', 'bdgogid');
        } else if ($this->mainActivityName == 'manual-activity') {
            $this->loadSelectDataForManualActivity();
            $this->getManualConnection($data);
            $this->groups = Customer::orderBy('customer_name', 'asc')->get();
            $this->groups = $this->groups->pluck('customer_name', 'bdgogid');

            if (!empty($inputs['counter_start']) && !empty($inputs['counter_end'])) {
                $this->addManualActivity($data);
            }
            if (empty($data['connection'])) {
                $pluckOnLink = SharedUserLink::all()->pluck('user_id');
                $sharedUser = SharedUser::query()->active(true)->whereNotIn('id', $pluckOnLink)->where('email', auth()->user()->email)->first();
                $this->userid = !empty($sharedUser->id) ? $sharedUser->id : null;
            }
        }

        // maintain current state after child modal closed.
        if ($prevInputData) {
            $this->setExistingStateForConnection($prevInputData);
        }

        if (!empty($this->selectedCustomer->bdgogid) && $this->mainActivityName == 'manual-activity') {
            $this->customerChange($this->selectedCustomer->bdgogid);
        }
    }

    public function rules(): array
    {
        return [
            'bdgogid' => ['required'],
            'groupname' => [$this->mainActivityName == 'edit-connection' ? 'required' : 'nullable'],
            'userid' => ['required'],
            'username' => [$this->mainActivityName == 'edit-connection' ? 'required' : 'nullable'],
            'device_id' => [$this->mainActivityName == 'edit-connection' ? 'required' : 'nullable'], // check for manual activity also.
            'devicename' => [$this->mainActivityName == 'edit-connection' ? 'required' : 'nullable'],
            'start_date' => ['required', $this->mainActivityName == 'edit-connection' ? 'date_format:d.m.Y H:i' : 'date_format:d.m.Y', 'before_or_equal:end_date'],
            'end_date' => ['required', $this->mainActivityName == 'edit-connection' ? 'date_format:d.m.Y H:i' : 'date_format:d.m.Y', 'after_or_equal:start_date'],
            'start_time' => $this->mainActivityName == 'edit-connection' ? ['nullable'] : ['required_with:end_time', 'date_format:H:i:s', 'before_or_equal:end_time'],
            'end_time' => $this->mainActivityName == 'edit-connection' ? ['nullable'] : ['required_with:start_time', 'date_format:H:i:s', 'after_or_equal:start_time'],
            'topic' => ['nullable', 'string'],
            'billing_state' => ['required'],
            'tariff_id' => ['nullable'],
            'activity_report' => ['nullable'],
            'notes' => ['nullable'],
            'cont_id' => ['nullable'],
            'contact_type' => $this->mainActivityName == 'edit-connection' ? ['nullable'] : ['nullable', 'in:1,2,3,4,5'],
        ];
    }

    public function validateConnectionData(): array
    {
        $validateInputs = [
            'bdgogid' => $this->bdgogid,
            'groupname' => $this->groupname,
            'userid' => $this->userid,
            'username' => $this->username,
            'device_id' => $this->device_id,
            'devicename' => $this->devicename,
            'topic' => $this->topic,
            'billing_state' => $this->billing_state,
            'tariff_id' => $this->tariff_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'activity_report' => $this->activity_report,
            'notes' => $this->notes,
            'cont_id' => $this->cont_id,
        ];

        if ($this->mainActivityName == 'edit-connection') {
            $validateInputs['support_session_type'] = 1;
        } else if ($this->mainActivityName == 'manual-activity') {
            $validateInputs['start_time'] = $this->start_time;
            $validateInputs['end_time'] = $this->end_time;
            $validateInputs['contact_type'] = $this->contact_type;
        }
        return $validateInputs;
    }

    public function setMainActivity($activityName): void
    {
        $this->mainActivityName = $activityName;
    }

    public function render()
    {
        if ($this->mainActivityName == 'edit-connection') {
            $this->loadSelectDataForEditConnection();
        }
        return view('livewire.tenant.activity-form-component')
            ->extends('tenant.layouts.contentLayoutMaster');
    }

    public function initSelect2()
    {
        $this->dispatchBrowserEvent('initSelect2');
    }

    public function updated($property)
    {
        $this->validateOnly($property, $this->rules());
        $this->initDevices();
    }

    public function updatedGroupId($value)
    {
        if ($value) {
            $this->selectedCustomer = Customer::where('bdgogid', $value)->first();
            $this->bdgogid = $this->selectedCustomer ? $this->selectedCustomer->bdgogid ?? "" : "";
            $this->groupname = $this->selectedCustomer->customer_name;
        }
    }

    public function updatedUserId($value)
    {
        $this->userid = null;
        $this->username = null;
        if ($value) {
            $sharedUser = SharedUser::where('id', $value)->first();
            $this->userid = $sharedUser->id;
            $this->username = $sharedUser->name;
        }
    }

    public function updatedDeviceId($value)
    {
        $this->device_id = null;
        $this->devicename = null;
        if ($value) {
            $device = Device::where('id', $value)->first();
            $this->device_id = $device->id;
            $this->devicename = $device->alias;
        }
    }

    public function createEditConnection($data)
    {
        if (key_exists('counter_start', $data) && key_exists('counter_end', $data)) {
            $this->start_date = $data['counter_start'];
            $this->end_date = $data['counter_end'];
        }

        if (key_exists('customer', $data)) {
            if (!empty($data['customer'])) {
                $customer = Customer::where('id', $data['customer'])->first();
            } else {
                $connectionReport = ConnectionReport::withTrashed()->find($data['connection']);
                $customer = !empty($connectionReport->bdgogid) ? Customer::where('bdgogid', $connectionReport->bdgogid)->first() : null;
            }
            if ($customer) {
                $this->selectedCustomer = $customer;
                $this->bdgogid = $this->selectedCustomer->bdgogid;
                $this->groupname = $this->selectedCustomer->customer_name;
            }
        }
        if (key_exists('connection', $data))
            if ($data['connection']) {
                $this->isEditMode = true;
                $this->selectedConnection = ConnectionReport::withTrashed()->find($data['connection']);
                $this->getModelDataEditConnection();
            } else
                $this->isEditMode = false;
    }

    public function loadSelectDataForEditConnection()
    {
        $this->initUsers();
        $this->initDevices();
        $this->initTariffs();
        $this->initContacts();
    }

    public function initCustomers()
    {
        $groups = Customer::orderBy('customer_name', 'asc')->get();
        $this->groups = $groups->pluck('customer_name', 'bdgogid');
    }

    public function initUsers()
    {
        if ($this->mainActivityName == 'edit-connection') {
            $this->users = collect();
            $this->users = SharedUser::query()->active(true)->orderBy('name')->pluck('id', 'name');
//        $users = $this->selectedCustomer ? ConnectionReport::withTrashed()->where('bdgogid',$this->selectedCustomer->bdgogid)->orderBy('username')->get() : ConnectionReport::withTrashed()->orderBy('username')->get();
//        foreach ($users->groupBy('username') as $key => $item){
//            $this->users->push(['key' => $item[0]->userid, 'value' => $key]);
//}
        } elseif ($this->mainActivityName == 'manual-activity') {
            $this->users = collect();
            $pluckOnLink = SharedUserLink::all()->pluck('user_id');
            $this->users = SharedUser::query()->active(true)->whereNotIn('id', $pluckOnLink)->orderBy('name')->pluck('id', 'name');
        }
    }

    public function initDevices()
    {
        $this->devices = Device::pluck('alias', 'id');
    }

    public function initTariffs()
    {
        $this->tariffs = collect();
        $tariffs = Tariff::where('overlap_status', false)->get()->filter(function ($tariff) {
            if ($tariff->global == true)
                return $tariff;
            elseif ($this->selectedCustomer) {
                if ($this->selectedCustomer->tariffs()->whereIn('tariff_id', [$tariff->id])->exists()) {
                    return $tariff;
                }
            }
        });

        if (count($tariffs))
            $this->tariffs = $tariffs->toQuery()->orderBy('tariff_name')->get();
        else
            $this->tariffs = Collection::empty();
    }

    public function initContacts()
    {
        if ($this->mainActivityName == 'edit-connection') {
            $contacts = $this->selectedCustomer ? Contact::select('id', 'bdgo_gid', 'firstname', 'lastname')->where('bdgo_gid', $this->selectedCustomer->bdgogid)->get() : Contact::select('id', 'bdgo_gid', 'firstname', 'lastname')->get();
            $this->contacts = collect($contacts)->pluck('full_name', 'id');
        } elseif ($this->mainActivityName == 'manual-activity') {
            $this->contacts = $this->selectedCustomer ? $this->selectedCustomer->contacts()->orderBy('firstname')->get() : Collection::empty();
        }
    }

    public function generateRandomOwnAppId(): string
    {
        return 'tenant' . Tenancy::getTenant()->getTenantKey() . '-' .
            strtolower($this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4)
                . '-' . $this->generateRandomString(12));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
    }

    public function getModelDataEditConnection()
    {
        if (!is_null($this->selectedConnection)) {
            $this->isVisible = $this->selectedConnection->trashed();
            $this->bdgogid = $this->selectedConnection->bdgogid;
            $this->groupname = $this->selectedConnection->groupname;
            $this->userid = $this->selectedConnection->userid;
            $this->username = $this->selectedConnection->username;
            $this->device_id = $this->selectedConnection->device_id;
            $this->devicename = $this->selectedConnection->devicename;
            if (!$this->devicename && $this->device_id) {
                $this->devicename = Device::find($this->device_id)->alias;
            }
            $this->start_date = $this->selectedConnection->start_date->setTimezone(config('site.default_timezone'))->format('d.m.Y H:i');
            $this->end_date = $this->selectedConnection->end_date->setTimezone(config('site.default_timezone'))->format('d.m.Y H:i');
            $this->dispatchBrowserEvent('load_flatpickrs', ['start' => $this->start_date, 'end' => $this->end_date]);
            $this->duration = AOHelpers::calculateDuration($this->selectedConnection->start_date, $this->selectedConnection->end_date);
            $this->billing_state = $this->selectedConnection->billing_state;
            $this->tariff_id = $this->selectedConnection->tariff_id;
            $this->activity_report = $this->selectedConnection->activity_report;
            $this->notes = $this->selectedConnection->notes;
            $this->cont_id = $this->selectedConnection->cont_id;

            $this->prevGroupId = $this->selectedConnection->bdgogid;
            $this->prevUserId = $this->selectedConnection->userid;
            $this->prevDeviceId = $this->selectedConnection->device_id;
            $this->prevGroupname = $this->selectedConnection->groupname;
            $this->prevUsername = $this->selectedConnection->username;
            $this->prevDevicename = $this->selectedConnection->devicename;
            $this->prevStartDate = $this->selectedConnection->start_date->setTimezone(config('site.default_timezone'))->format('d.m.Y H:i');
            $this->prevEndDate = $this->selectedConnection->end_date->setTimezone(config('site.default_timezone'))->format('d.m.Y H:i');
            $this->prevBillingState = $this->selectedConnection->billing_state;
            $this->prevTariffId = $this->selectedConnection->tariff_id;
            $this->prevActivityReport = $this->selectedConnection->activity_report;
            $this->prevNotes = $this->selectedConnection->notes;
            $this->prevContId = $this->selectedConnection->cont_id;

            $this->prevStatus = ($this->selectedConnection->booked ? 'booked' : ($this->selectedConnection->printed ? 'printed' : ''));
            $this->status = ($this->selectedConnection->booked ? 'booked' : ($this->selectedConnection->printed ? 'printed' : ''));
        }
    }

    public function save()
    {
        if ($this->mainActivityName == 'edit-connection') {
            $this->saveEditConnection();
        } else {
            $this->saveManualActivityConnection();
        }
    }

    public function saveEditConnection()
    {
        $flag = false;
        $overlappedChecks = false;
        $overlappedLoads = [];
        $validation = Validator::make($this->validateConnectionData(), $this->rules());
        if ($validation->fails()) {
            $error = $validation->getMessageBag();
            $this->dispatchBrowserEvent('focusErrorInput', ['field' => array_key_first($error->getMessages())]);
            $validation->validate();
        } else {
            if ($this->selectedConnection) {
                $data = [];
                $validatedData = [];
                if ($this->bdgogid != $this->prevGroupId) {
                    $data = array_merge($data, ['bdgogid' => $this->bdgogid, 'groupname' => $this->groupname]);
                    $validatedData = array_merge($validatedData, [
                        'bdgogid' => ['required'],
                        'groupname' => ['string'],
                    ]);
                    $overlappedChecks = true;
                }
                if ($this->device_id != $this->prevDeviceId) {
                    $data = array_merge($data, ['device_id' => $this->device_id]);
                    $validatedData = array_merge($validatedData, [
                        'device_id' => ['required']
                    ]);
                    $overlappedChecks = true;
                }
                if ($this->devicename != $this->prevDevicename) {
                    $data = array_merge($data, ['devicename' => $this->devicename]);
                    $validatedData = array_merge($validatedData, [
                        'devicename' => ['required']
                    ]);
                }

                // update status - printed or booked or null
                if ($this->status != $this->prevStatus) {
                    if ($this->status == 'booked') {
                        $this->selectedConnection->restore();
                        $this->selectedConnection->booked = true;
                        $this->selectedConnection->printed = false;
                    } elseif ($this->status == 'printed') {
                        $this->selectedConnection->restore();
                        $this->selectedConnection->booked = false;
                        $this->selectedConnection->printed = true;
                    } elseif ($this->status == '') {
                        $this->selectedConnection->booked = false;
                        $this->selectedConnection->printed = false;
                    }
                    $this->selectedConnection->save();
                }

                // disable editing start date and end date on edit connection activity.

                /*if (Carbon::createFromFormat('d.m.Y H:i',$this->start_date,config('site.default_timezone'))->format('d.m.Y H:i') != $this->prevStartDate) {
                    $data = array_merge($data, ['start_date' => Carbon::createFromFormat('d.m.Y H:i',$this->start_date,config('site.default_timezone'))->setTimezone('UTC')->format('d.m.Y H:i')]);
                    $validatedData = array_merge($validatedData, [
                        'start_date' => ['date_format:d.m.Y H:i']
                    ]);
                    $overlappedChecks = true;
                }
                if (Carbon::createFromFormat('d.m.Y H:i',$this->end_date,config('site.default_timezone'))->format('d.m.Y H:i') != $this->prevEndDate) {
                    $data = array_merge($data, ['end_date' => Carbon::createFromFormat('d.m.Y H:i',$this->end_date,config('site.default_timezone'))->setTimezone('UTC')->format('d.m.Y H:i')]);
                    $validatedData = array_merge($validatedData, [
                        'end_date' => ['date_format:d.m.Y H:i']
                    ]);
                    $overlappedChecks = true;
                }*/
                if ($this->tariff_id != $this->prevTariffId) {
                    if ($this->tariff_id) {
                        $this->selectedConnection->tariff_id = $this->tariff_id;
                        $this->selectedConnection->save();
                        $price = $this->selectedConnection->calculatePrice();
                        $this->selectedConnection->update(['price' => $price]);
                    } else {
                        $this->selectedConnection->tariff_id = null;
                        $this->selectedConnection->price = null;
                        $this->selectedConnection->save();
                    }
                    $flag = true;
                }
                if ($this->billing_state != $this->prevBillingState) {
                    if($this->selectedConnection->booked != true){
                        $this->selectedConnection->billing_state = $this->billing_state;
                        $this->selectedConnection->save();
                        if ($this->prevBillingState == 'Hide') {
                            $this->selectedConnection->restore();
                        }
                        if ($this->selectedConnection->billing_state == 'Hide') {
                            $this->selectedConnection->printed = false;
                            $this->selectedConnection->booked = false;
                            $this->selectedConnection->save();
                            $this->selectedConnection->delete();
                        }
                    }else{
                        $this->billing_state = $this->prevBillingState;
                    }

                    $overlappedChecks = true;
                    $overlappedLoads['billing_state'] = $this->billing_state;
                    $flag = true;
                }
                if ($this->activity_report != $this->prevActivityReport) {
                    $data = array_merge($data, ['activity_report' => $this->activity_report]);
                    $validatedData = array_merge($validatedData, [
                        'activity_report' => 'nullable'
                    ]);
                }
                if ($this->notes != $this->prevNotes) {
                    $data = array_merge($data, ['notes' => $this->notes]);
                    $validatedData = array_merge($validatedData, [
                        'notes' => 'nullable'
                    ]);
                }
                if ($this->cont_id != $this->prevContId) {
                    $data = array_merge($data, ['cont_id' => $this->cont_id]);
                    $validatedData = array_merge($validatedData, [
                        'cont_id' => ['nullable']
                    ]);
                }
                $validate = Validator::make($data, $validatedData)->validate();
                if (count($validate)) {
                    $this->selectedConnection->update($validate);

                    if ($overlappedChecks) {
                        $this->selectedConnection->refresh();
                        $exec = new OverlapsEvaluation($this->selectedConnection);

                        if ($this->selectedConnection->overlaps_user && key_exists('billing_state', $overlappedLoads) && ($overlappedLoads['billing_state'] == 'DoNotBill' || $overlappedLoads['billing_state'] == 'Hide')) {
                            $exec::overlaps_unchecks();
                        } else {
                            if ($this->selectedConnection->overlaps_user) {
                                $exec::overlaps_unchecks();
                            }
                            $exec::overlaps_check();
                        }
                    }
                    $flag = true;
                }
                if ($flag) {

                    if ($this->selectedConnection) {
                        // check overlaps tariff & update connection accordingly for edit activity.
                        $this->selectedConnection->borderLineEmergency();
                    }
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Connection Updated!')]);
                }
            }
            $this->dispatchBrowserEvent('refreshConnectionReports');
//            $this->emitTo('tenant.customer-connections-component', 'refresh');
        }
        $this->emit('hideModal');
    }

    public function saveManualActivityConnection()
    {
        $validation = Validator::make($this->validateConnectionData(), $this->rules());
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
                    $group = Customer::query()->where('bdgogid', $this->bdgogid)->first();
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
                        'devicename' => !empty($device->name) ? $device->name : ''
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
                        'topic' => ['nullable', 'string']
                    ]);
                }
                if ($this->billing_state != $this->prevBillingState) {
                    $model = ConnectionReport::withTrashed()->where('id', $this->selectedConnection->id)->first();

                    // Can change billing status, if connection is not booked
                    // Otherwise not able to change billing status
                    if($model->booked != true){
                        $model->update([
                            'billing_state' => $this->billing_state
                        ]);
                        if ($this->prevBillingState == 'Hide') {
                            $model->restore();
                        }
                        if ($model->billing_state == 'Hide') {
                            $model->printed = false;
                            $model->booked = false;
                            $model->save();
                            $model->delete();
                        }
                    }else{
                        $this->billing_state = $this->prevBillingState;
                    }

                    $overlappedLoads['billing_state'] = $this->billing_state;
                    $overlappedCheck = true;
                }
                if ((Carbon::createFromFormat('d.m.Y', $this->start_date, $this->timezone)->format('d.m.Y') != $this->prevStartDate) || (Carbon::createFromFormat('H:i:s', $this->start_time, $this->timezone)->format('H:i:s') != $this->prevStartTime)) {
                    $data = array_merge($data, ['start_date' => Carbon::createFromFormat('d.m.Y H:i:s', $this->start_date . " " . $this->start_time, $this->timezone)->setTimezone('UTC')->format('d.m.Y H:i:s')]);
                    $validateData = array_merge($validateData, [
                        'start_date' => ['date_format:d.m.Y H:i:s']
                    ]);
                    $overlappedCheck = true;
                }
                if ((Carbon::createFromFormat('d.m.Y', $this->end_date, $this->timezone)->format('d.m.Y') != $this->prevEndDate) || (Carbon::createFromFormat('H:i:s', $this->end_time, $this->timezone)->format('H:i:s') != $this->prevEndTime)) {
                    $data = array_merge($data, ['end_date' => Carbon::createFromFormat('d.m.Y H:i:s', $this->end_date . " " . $this->end_time, $this->timezone)->setTimezone('UTC')->format('d.m.Y H:i:s')]);
                    $validateData = array_merge($validateData, [
                        'end_date' => ['date_format:d.m.Y H:i:s']
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
                        'contact_type' => ['nullable', 'in:1,2,3,4,5']
                    ]);
                }

                if ($this->tariff_id != $this->prevTariffId) {
                    $data = array_merge($data, ['tariff_id' => $this->tariff_id]);
                    $validateData = array_merge($validateData, [
                        'tariff_id' => ['nullable']
                    ]);
                }

                $validator = Validator::make($data, $validateData)->validate();
                if (count($validator)) {
                    $connection = ConnectionReport::where('id', $this->selectedConnection->id)->withTrashed()->first();
                    $connection->update($validator);
                    $service = new ManualActivityTariffApplying($connection, ['bdgogid' => $this->bdgogid]);
                    $tariff_applying = $service::applyTariffToConnection();
                    if ($overlappedCheck) {
                        $connection->refresh();
                        $exec = new OverlapsEvaluation($connection);
                        if ($connection->overlaps_user && key_exists('billing_state', $overlappedLoads) && ($overlappedLoads['billing_state'] == 'DoNotBill' || $overlappedLoads['billing_state'] == 'Hide')) {
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
            } else {
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
                    'tariff_id' => $this->tariff_id,
                    'start_date' => Carbon::createFromFormat('d.m.Y H:i:s', $this->start_date . " " . $this->start_time, $this->timezone)->setTimezone('UTC'),
                    'end_date' => Carbon::createFromFormat('d.m.Y H:i:s', $this->end_date . " " . $this->end_time, $this->timezone)->setTimezone('UTC'),
                    'notes' => $this->notes,
                    'activity_report' => $this->activity_report,
                    'isTV' => false,
                    'cont_id' => !empty($this->cont_id) ? $this->cont_id : null,
                    'contact_type' => $this->contact_type
                ]);
                $service = new ManualActivityTariffApplying($connection, ['bdgogid' => $this->bdgogid]);
                $tariff_applying = $service::applyTariffToConnection();
                $exec = new OverlapsEvaluation($connection);
                $exec::overlaps_check();
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Connection Created!')]);
            }

            if (!empty($connection)) {
                // check overlaps tariff & update connection accordingly for manual activity.
                $connection->borderLineEmergency();
            }

            $this->emitTo('tenant.customer-connections-component', 'refresh');
            $this->dispatchBrowserEvent('refreshConnectionReports');
        }
        $this->closedActivityModal();
    }

    public function closedActivityModal()
    {
        if ($this->mainActivityName == 'edit-connection') {
            $this->resetEditConnection();
            $this->emit('hideModal');
        } elseif ($this->mainActivityName == 'manual-activity') {
            $this->clearVariablesForManualActivity();
            $this->emit('hideModal');
        }
    }

    public function resetEditConnection()
    {
        $this->selectedConnection = null;
        $this->selectedCustomer = null;
        $this->isEditMode = false;

        $this->bdgogid = null;
        $this->userid = null;
        $this->device_id = null;
        $this->groupname = null;
        $this->username = null;
        $this->devicename = null;
        $this->support_session_type = 1;
        $this->start_date = null;
        $this->end_date = null;
        $this->billing_state = null;
        $this->tariff_id = null;
        $this->activity_report = null;
        $this->notes = null;
        $this->cont_id = null;

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

        $this->resetValidation();
    }

    public function setExistingStateForConnection($connectionData)
    {
        $data = json_decode($connectionData);
        if ($this->mainActivityName == 'edit-connection') {
            $this->mainActivityName = $data->mainActivityName;
            $this->selectedConnection = !empty($this->selectedConnection->id) ? ConnectionReport::withTrashed()->where('id', $this->selectedConnection->id)->first() : null;
            $this->selectedCustomer = !empty($data->selectedCustomer->bdgogid) ? Customer::where('bdgogid', $data->selectedCustomer->bdgogid)->first() : null;
            $this->isEditMode = $data->isEditMode;
            $this->bdgogid = $data->bdgogid;
            $this->userid = $data->userid;
            $this->device_id = $data->device_id;
            $this->groupname = $data->groupname;
            $this->username = $data->username;
            $this->devicename = $data->devicename;
            $this->support_session_type = $data->support_session_type;
            $this->start_date = $data->start_date;
            $this->end_date = $data->end_date;
            $this->billing_state = $data->billing_state;
            $this->tariff_id = $data->tariff_id;
            $this->activity_report = $data->activity_report;
            $this->notes = $data->notes;
            $this->cont_id = $data->cont_id;
        } else {
            $this->selectedConnection = !empty($this->selectedConnection->id) ? ConnectionReport::withTrashed()->where('id', $this->selectedConnection->id)->first() : null;
            $this->selectedCustomer = !empty($data->selectedCustomer->bdgogid) ? Customer::where('bdgogid', $data->selectedCustomer->bdgogid)->first() : null;
            $this->contact_name = $data->contact_name;
            $this->bdgogid = $data->bdgogid;
            $this->userid = $data->userid;
            $this->device_id = $data->device_id;
            $this->groupname = $data->groupname;
            $this->username = $data->username;
            $this->devicename = $data->devicename;
            $this->start_date = $data->start_date;
            $this->end_date = $data->end_date;

            $this->reset(['billing_state']);

            $this->tariff_id = $data->tariff_id;
            $this->activity_report = $data->activity_report;
            $this->notes = $data->notes;
            $this->cont_id = $data->cont_id;
            $this->contact_type = $data->contact_type;
            $this->topic = $data->topic;
            $this->start_time = $data->start_time;
            $this->end_time = $data->end_time;
        }
        $this->resetValidation();
    }


    /* Edit connection misc. functions */

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

    public function openCustomerModal()
    {
        $this->emit('showModal', 'tenant.customer-modal-component', $this->mainActivityName, $this);
    }

    public function openDeviceModal()
    {
        $this->emit('showModal', 'tenant.device-modal-component', $this->mainActivityName, $this);
    }

    public function openContactModal()
    {
        $this->emit('showModal', 'tenant.contact-modal-component', $this->mainActivityName, $this);
    }

    public function openUserModal()
    {
        $this->emit('showModal', 'tenant.user-modal-component', $this->mainActivityName, $this);
    }

    public function showContactModal()
    {
        $this->emitTo('tenant.customer-connections-component', 'shownContactModal', $this->selectedCustomer, 'addContactModalEditConnection');
        $this->emitTo('tenant.contact-modal-component', 'setSelectedCustomer', $this->selectedCustomer);
    }

    public function showDeviceModal()
    {
        $this->emitTo('tenant.customer-connections-component', 'shownDeviceModal', $this->selectedCustomer, 'addDeviceModalEditConnection');
        $this->emitTo('tenant.device-modal-component', 'setSelectedCustomer', $this->selectedCustomer);
    }

    /* Manual activity functions */

    public function getManualConnection($data)
    {
        if (key_exists('connection', $data) && !is_null($data['connection'])) {
            $model = $this->selectedConnection = ConnectionReport::withTrashed()->find($data['connection']);
            $this->bdgogid = $model->bdgogid;
            if ($this->bdgogid) {
                $this->selectedCustomer = Customer::query()->group($this->bdgogid)->first();
                $this->groupname = $this->selectedCustomer ? $this->selectedCustomer->customer_name : "";
                $this->loadSelectDataForManualActivity();
            }

            $this->userid = $model->userid;
            $this->device_id = $model->device_id;
            if (!$this->devicename && $this->device_id) {
                $this->devicename = Device::find($this->device_id)->alias;
            }
            $this->topic = $model->topic;
            $this->billing_state = $model->billing_state;
            $this->start_date = $model->start_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->end_date = $model->end_date->setTimezone($this->timezone)->format('d.m.Y');
            $this->start_time = $model->start_date->setTimezone($this->timezone)->format('H:i:s');
            $this->end_time = $model->end_date->setTimezone($this->timezone)->format('H:i:s');
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
            $this->prevStartTime = $model->start_date->setTimezone($this->timezone)->format('H:i:s');
            $this->prevEndTime = $model->end_date->setTimezone($this->timezone)->format('H:i:s');
            $this->duration = $model->duration();
            $this->prevActivityReport = $model->activity_report;
            $this->prevNotes = $model->notes;
            $this->prevContId = $model->cont_id;
            $this->prevContactType = $model->contact_type;
            $this->prevTariffId = $model->tariff_id;
        } else {
            // Set contact type "Phone Call".
            // As per Susann Teamviewer type is only usable for Teamviewer import.
            $this->contact_type = 2;
        }
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

        if (key_exists('customer', $data)) {
            if ($data['customer']) {
                $this->selectedCustomer = Customer::where('id', $data['customer'])->orWhere('bdgogid', $data['customer'])->first();
                if ($this->selectedCustomer) {
                    $this->bdgogid = $this->selectedCustomer ? $this->selectedCustomer->bdgogid ?? "" : "";
                    $this->groupname = $this->selectedCustomer->customer_name;
                }
            }
        }
        $this->loadSelectDataForManualActivity();

        if (key_exists('counter_start', $data) && key_exists('counter_end', $data)) {
            $this->start_date = !empty($this->date_input($data['counter_start'])) ? $this->date_input($data['counter_start']) : now()->setTimezone($this->timezone)->format('d.m.Y');
            $this->end_date = !empty($this->date_input($data['counter_end'])) ? $this->date_input($data['counter_end']) : now()->setTimezone($this->timezone)->format('d.m.Y');
            $this->start_time = $data['counter_start'] ? date('H:i:s', strtotime($data['counter_start'])) : null;
            $this->end_time = $data['counter_end'] ? date('H:i:s', strtotime($data['counter_end'])) : null;
            $this->duration = $data['counter_start'] && $data['counter_end']
                ? AOHelpers::calculateDuration(Carbon::createFromFormat('d.m.Y H:i:s', $data['counter_start']), Carbon::createFromFormat('d.m.Y H:i:s', $data['counter_end']))
                : null;
        }

        $this->userid = auth()->id();
        if ($this->userid) {
            $user = SharedUser::query()->whereKey($this->userid)->first();
            $this->username = $user->name;
        }
    }

    public function customerChange($value)
    {
        if ($value) {
            $this->selectedCustomer = Customer::where('bdgogid', $value)->first();
            $this->bdgogid = $this->selectedCustomer ? $this->selectedCustomer->bdgogid ?? "" : "";
            $this->groupname = $this->selectedCustomer ? $this->selectedCustomer->customer_name : "";
            $this->reset(['device_id']);
            $this->cont_id = "";
            $this->contact_name = "";
        } else {
            $this->selectedCustomer = null;
            $this->bdgogid = null;
            $this->groupname = null;
            $this->reset(['device_id']);
            $this->cont_id = "";
            $this->contact_name = "";
        }

        if ($this->mainActivityName != 'manual-activity') {
            $this->loadSelectDataForManualActivity();
        }

        $this->emitTo('tenant.contact-modal-component', 'setSelectedCustomer', $this->selectedCustomer);
        $this->emitTo('tenant.device-modal-component', 'setSelectedCustomer', $this->selectedCustomer);
    }

    public function loadSelectDataForManualActivity()
    {
        $this->initUsers();
        $this->initDevices();
        $this->initDate();
        $this->initTariffs();
        $this->initContacts();
        $this->setTimezone();
    }

    public function initDate()
    {
        $this->start_date = now()->format('d.m.Y');
        $this->end_date = now()->format('d.m.Y');
    }

    public function setTimezone($timezone = null)
    {
        if ($timezone) {
            $this->timezone = $timezone;
        } else {
            $this->dispatchBrowserEvent('setTimezone');
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

    public function clearVariablesForManualActivity()
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

    public function time_input($time)
    {
        $list = explode(" ", $time);
        return $list[1];
    }

    public function date_input($date)
    {
        $list = explode(" ", $date);
        return $list[0];
    }

    /**
     * For check connection edit/create model loaded or not.
     * Uses wire:init="modelLoaded"
     */
    public function modelLoaded()
    {}

    public function setHourMinutePicker()
    {
        $this->dispatchBrowserEvent('setHourMinutePicker', ['start_time' => $this->start_time, 'end_time' => $this->end_time]);
    }
}
