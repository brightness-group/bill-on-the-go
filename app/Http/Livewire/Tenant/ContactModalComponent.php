<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Session\ContactUserSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ContactModalComponent extends Component
{
    public $connectionData;
    public $activityName;
    public $actionType = null;
    public $selectedCustomer = null;
    public $contacts, $devices, $selectedItem;

    public $selectedDevice = [];
    public $salutation = null;
    public $firstname, $lastname, $c_department, $c_function, $s_email, $p_email, $b_number, $m_number, $h_number;
    public $prevSalutation, $prevFirstName, $prevLastName, $prevCDepartment, $prevCFunction, $prevSEmail, $prevPEmail, $prevBNumber, $prevMNumber, $prevHNumber, $prevSelectedDevice;

    public $listeners = [
        'refresh' => '$refresh',
        'devicesSelect',
        'selectedCustomer',
        'cleanVars',
        'setSelectedCustomer',
    ];

    public function mount($activityName, $connectionData = null)
    {
        $this->activityName = $activityName;
        $this->connectionData = $connectionData;
        $this->selectedCustomer = !empty($connectionData['selectedCustomer']['bdgogid'])
            ? Customer::where('bdgogid', $connectionData['selectedCustomer']['bdgogid'])->first() : null;
    }

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

    public function render()
    {
        return view('livewire.tenant.contact-modal-component');
    }

    public function updated($properties)
    {
        $this->validateOnly($properties,$this->rules());
    }

    public function selectedCustomer($value)
    {
        $this->selectedCustomer = Customer::where('bdgogid',$value['bdgogid'])->first();
        $this->loadData();
    }

    public function devicesSelect($value)
    {
        $this->selectedDevice = $value;
    }

    public function refreshLoadData()
    {
        $this->selectedCustomer->refresh();
        $this->loadData();
    }

    public function loadData()
    {
        if ($this->selectedCustomer) {
            $this->contacts = $this->selectedCustomer->contacts()->get();
            $this->devices = $this->selectedCustomer->devices()->orderBy('alias')->get();
        } else {
            $contacts_user_session = auth()->user()->contact_user_session()->where('session_id',session()->getId())->pluck('contact_id');
            if (count($contacts_user_session)) {
                $this->contacts = Contact::query()->whereNull('bdgo_gid')->whereIn('id',$contacts_user_session)->get();
            }
            else
                $this->contacts = Collection::empty();
        }
    }

    public function save()
    {
        $flag = false;
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
            if ($this->selectedCustomer) {
                $contact = Contact::query()->create($validator +
                    [
                        'bdgo_gid' => $this->selectedCustomer->bdgogid
                    ]);
                if (is_array($this->selectedDevice) && count($this->selectedDevice)){
                    $contact->devices()->attach($this->selectedDevice);
                }
                $flag = true;
            }
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Contact Created!')]);
        }
        if ($flag) {
            if ($this->selectedCustomer)
                $this->contacts = $this->selectedCustomer->contacts()->get();
            else {
                $contacts_user_session = auth()->user()->contact_user_session()->where('session_id',session()->getId())->pluck('contact_id');
                if (count($contacts_user_session))
                    $this->contacts = Contact::query()->whereNull('bdgo_gid')->whereIn('id',$contacts_user_session)->get();
            }

            if (!empty($contact->id)) {
                $this->connectionData['cont_id'] = $contact->id;
            }
        }
        $this->closeContactModal();
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

    public function setSelectedCustomer($customerData)
    {
        $this->selectedCustomer = null;
        if(!empty($customerData['id'])) {
            $this->selectedCustomer = Customer::find($customerData['id']);
        }
        $this->loadData();
    }

    public function closeContactModal()
    {
        $this->cleanVars();
        $item = !empty($this->connectionData['selectedConnection']['id']) ? $this->connectionData['selectedConnection']['id'] : null;
        $this->emit('showModal', 'tenant.activity-form-component', $this->activityName, json_encode(['item' => $item, 'customer' => $this->connectionData['selectedCustomer']]), json_encode($this->connectionData));
    }
}
