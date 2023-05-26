<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Session\ContactUserSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ContactComponent extends Component
{
    public ?Customer $customer = null;
    public $contacts, $devices, $selectedItem;

    public $selectedDevice = [];
    public $salutation = 1;
    public $firstname, $lastname, $c_department, $c_function, $s_email, $p_email, $b_number, $m_number, $h_number;
    public $prevSalutation, $prevFirstName, $prevLastName, $prevCDepartment, $prevCFunction, $prevSEmail, $prevPEmail, $prevBNumber, $prevMNumber, $prevHNumber, $prevSelectedDevice;

    public $full_name = "";

    public $listeners = [
        'refresh' => '$refresh',
        'devicesSelect',
        'cleanVars'
    ];

    public function rules()
    {
        return [
            'salutation' => ['boolean'],
            'firstname' => ['required','string','min:2'],
            'lastname' => ['required','string','min:2'],
            'c_department' => ['nullable'],
            'c_function' => ['nullable','string'],
            's_email' => ['nullable','regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/'],
            'p_email' => ['nullable','regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/'],
            'b_number' => ['nullable'],
            'm_number' => ['nullable'],
            'h_number' => ['nullable'],
        ];
    }

    public function hydrate()
    {
        $this->emit('loadContactDeviceSelect2');
    }

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.tenant.contact-component');
    }

    public function updated($properties)
    {
        $this->validateOnly($properties,$this->rules());
    }

    public function devicesSelect($value)
    {
        $this->selectedDevice = $value;
    }

    public function loadData()
    {
        if ($this->customer) {
            $this->contacts = $this->customer->contacts()->get();
            $this->devices = $this->customer->devices()->orderBy('alias')->get();
        } else {
            $contacts_user_session = auth()->user()->contact_user_session()->where('session_id',session()->getId())->pluck('contact_id');
            if (count($contacts_user_session)) {
                $this->contacts = Contact::query()->whereNull('bdgo_gid')->whereIn('id',$contacts_user_session)->get();
            }
            else
                $this->contacts = Collection::empty();
        }
    }

    public function getModelId()
    {
        $model = Contact::query()->where('id',$this->selectedItem)->first();

        $this->salutation = $model->salutation;
        $this->firstname = $model->firstname;
        $this->lastname = $model->lastname;
        $this->c_department = $model->c_department;
        $this->c_function = $model->c_function;
        $this->s_email = $model->s_email;
        $this->p_email = $model->p_email;
        $this->b_number = $model->b_number;
        $this->m_number = $model->m_number;
        $this->h_number = $model->h_number;
        $this->selectedDevice = $model->devices()->pluck('id')->toArray();

        $this->prevSalutation = $model->salutation;
        $this->prevFirstName = $model->firstname;
        $this->prevLastName = $model->lastname;
        $this->prevCDepartment = $model->c_department;
        $this->prevCFunction = $model->c_function;
        $this->prevSEmail = $model->s_email;
        $this->prevPEmail = $model->p_email;
        $this->prevBNumber = $model->b_number;
        $this->prevMNumber = $model->m_number;
        $this->prevHNumber = $model->h_number;
        $this->prevSelectedDevice = $model->devices()->pluck('id')->toArray();
    }

    public function toggleContactDiv()
    {
        $this->dispatchBrowserEvent('toggleContactDiv');
    }

    public function selectItem($modelId, $action)
    {
        $this->selectedItem = $modelId;

        if ($action == 'delete') {
            $contact = Contact::query()->where('id', $this->selectedItem)->first();

            $this->full_name = (!empty($contact)) ? $contact->full_name : "";

            $this->dispatchBrowserEvent('openContactModalDelete');
        }
        else {
            $this->getModelId();
            $this->dispatchBrowserEvent('openToggleContactDiv');
        }
    }

    public function save()
    {
        $flag = false;
        if ($this->selectedItem) {
            $data = [];
            $validateData = [];

            if ($this->salutation != $this->prevSalutation) {
                $data = array_merge($data, ['salutation' => $this->salutation]);
                $validateData = array_merge($validateData, [
                    'salutation' => ['boolean']
                ]);
            }
            if ($this->firstname != $this->prevFirstName) {
                $data = array_merge($data, ['firstname' => $this->firstname]);
                $validateData = array_merge($validateData, [
                    'firstname' => ['string','min:2']
                ]);
            }
            if ($this->lastname != $this->prevLastName) {
                $data = array_merge($data, ['lastname' => $this->lastname]);
                $validateData = array_merge($validateData, [
                    'lastname' => ['string','min:2']
                ]);
            }
            if ($this->c_department != $this->prevCDepartment) {
                $data = array_merge($data, ['c_department' => $this->c_department]);
                $validateData = array_merge($validateData, [
                    'c_department' => ['nullable']
                ]);
            }
            if ($this->c_function != $this->prevCFunction) {
                $data = array_merge($data, ['c_function' => $this->c_function]);
                $validateData = array_merge($validateData, [
                    'c_function' => ['nullable','string']
                ]);
            }
            if ($this->s_email != $this->prevSEmail) {
                $data = array_merge($data, ['s_email' => $this->s_email]);
                $validateData = array_merge($validateData, [
                    's_email' => ['nullable','regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/']
                ]);
            }
            if ($this->p_email != $this->prevPEmail) {
                $data = array_merge($data, ['p_email' => $this->p_email]);
                $validateData = array_merge($validateData, [
                    'p_email' => ['nullable','regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/']
                ]);
            }
            if ($this->b_number != $this->prevBNumber) {
                $data = array_merge($data, ['b_number' => $this->b_number]);
                $validateData = array_merge($validateData, [
                    'b_number' => ['nullable']
                ]);
            }
            if ($this->m_number != $this->prevMNumber) {
                $data = array_merge($data, ['m_number' => $this->m_number]);
                $validateData = array_merge($validateData, [
                    'm_number' => ['nullable']
                ]);
            }
            if ($this->h_number != $this->prevHNumber) {
                $data = array_merge($data, ['h_number' => $this->h_number]);
                $validateData = array_merge($validateData, [
                    'h_number' => ['nullable']
                ]);
            }

            $validator = Validator::make($data, $validateData)->validate();
            $contact = Contact::query()->where('id', $this->selectedItem)->first();
            if (count($validator)) {
                $contact->update($validator);
                $flag = true;
            }
            if (!(array_diff($this->selectedDevice,$this->prevSelectedDevice) == [] && array_diff($this->prevSelectedDevice,$this->selectedDevice) == [])) {
                $contact->devices()->detach($this->prevSelectedDevice);
                $contact->devices()->attach($this->selectedDevice);
                $flag = true;
            }
            if ($flag)
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Contact Updated!')]);
        } else {
            $validator = Validator::make(
                [
                    'salutation' => $this->salutation == 1,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'c_department' => $this->c_department,
                    'c_function' => $this->c_function,
                    's_email' => $this->s_email,
                    'p_email' => $this->p_email,
                    'b_number' => $this->b_number,
                    'm_number' => $this->m_number,
                    'h_number' => $this->h_number,
                ]
                , $this->rules())->validate();
            if (count($validator)) {
                if ($this->customer) {
                    $contact = Contact::query()->create($validator +
                        [
                            'bdgo_gid' => $this->customer->bdgogid
                        ]);
                    if (count($this->selectedDevice))
                        $contact->devices()->attach($this->selectedDevice);
                    $flag = true;
                } else {
                    $contact = Contact::create($validator);
                    $contact->contact_user_session()->create([
                        'user_id' => auth()->user()->id,
                        'contact_id' => $contact->id,
                        'session_id' => session()->getId()
                    ]);
                    $flag = true;
                }
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Contact Created!')]);
            }
        }
        if ($flag) {
            if ($this->customer)
                $this->contacts = $this->customer->contacts()->get();
            else {
                $contacts_user_session = auth()->user()->contact_user_session()->where('session_id',session()->getId())->pluck('contact_id');
                if (count($contacts_user_session))
                    $this->contacts = Contact::query()->whereNull('bdgo_gid')->whereIn('id',$contacts_user_session)->get();
            }
            $this->emitSelf('refresh');
        }
        $this->endProcess();
    }

    public function destroy()
    {
        Contact::query()->find($this->selectedItem)->delete();
        $this->contacts = $this->customer->contacts()->get();
        $this->emitSelf('refresh');
        $this->closeContactModalDelete();
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Contact Deleted!')]);
    }

    public function closeContactModalDelete()
    {
        $this->selectedItem = null;
        $this->dispatchBrowserEvent('closeContactModalDelete');
    }

    public function endProcess()
    {
        $this->dispatchBrowserEvent('closeToggleContactDiv');
        $this->cleanVars();
    }

    public function cancel()
    {
        $this->endProcess();
    }

    public function cleanVars()
    {
        $this->reset(['salutation']);
        $this->selectedItem = null;

        $this->selectedDevice = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->c_department = null;
        $this->c_function = null;
        $this->s_email = null;
        $this->p_email = null;
        $this->b_number = null;
        $this->m_number = null;
        $this->h_number = null;

        $this->prevSalutation = null;
        $this->prevFirsName = null;
        $this->prevLastName = null;
        $this->prevCDepartment = null;
        $this->prevCFunction = null;
        $this->prevSEmail = null;
        $this->prevPEmail = null;
        $this->prevBNumber = null;
        $this->prevMNumber = null;
        $this->prevHNumber = null;

        $this->resetValidation();
    }

}
