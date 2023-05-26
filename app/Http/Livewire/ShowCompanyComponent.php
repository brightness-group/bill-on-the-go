<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Bdgo\CustomerType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Tenancy\Facades\Tenancy;
use Image;

class ShowCompanyComponent extends Component
{

    use WithFileUploads;

    public $name, $subdomain, $logo, $email, $address, $payment, $iban, $bic, $notes, $zip, $city, $country, $contact, $contact_email, $anydesk_client_id, $anydesk_client_secret, $customer_type_id = Company::DEFAULT_CUSTOMER_TYPE_ID, $modelId;
    public $prevName, $prevSubdomain, $prevLogo, $prevAddress, $prevEmail, $prevPayment, $prevIban, $prevBic, $prevNotes, $prevZip, $prevCity, $prevCountry, $prevContact, $prevContactEmail, $prevTeamviewerClientId, $prevTeamviewerClientSecret, $prevCustomerTypeId;

    public ?Company $company = null;
    public string $tab = 'basic';
    public bool $toastrMsg = false;

    public bool $isCreate = true;

    public $customerTypes;

    public $listeners = [
        'updateField'
    ];

    public function mount(Company $company = null, $isDeletePhoto = false, $tab = 'basic')
    {
        $this->isCreate = !(!empty($company) && !empty($company->id));

        if (!$this->isCreate) {
            $this->company = $company;
            $this->getModelId($this->company->id);
        }

        if ($isDeletePhoto) {
            $this->deletePhoto();
        }

        $this->tab = $tab;

        $this->customerTypes = CustomerType::all();
    }

    public function dehydrate()
    {
        $this->emit('parentComponentErrorBag', $this->getErrorBag());
    }

    public function render()
    {
        return view('livewire.show-company-component');
    }

    public function updated($subdomain)
    {
        $this->resetValidation();
        $this->validateOnly($subdomain, $this->rules() + [
                'logo' => ['nullable','image', 'max:1024'],  //mimes:jpg,png,jpeg
            ]);
    }

    public function rules(): array
    {
        $rules = [];
        if ($this->modelId) {
            $rules = [
                'name' => ['required','min:2', Rule::unique('companies')->ignore($this->modelId, 'id')],
                'subdomain' => ['required','regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])?$/', Rule::unique('companies')->ignore($this->modelId, 'id')],
//                'logo' => ['nullable','image', 'max:1024'],  //mimes:jpg,png,jpeg
                'address' => ['nullable', 'min:6'],
                'zip' => ['nullable', 'min:2'],
                'city' => ['nullable','required_with:country'],
                'country' => ['required_with:city'],
                'email' => ['nullable', Rule::unique('companies')->ignore($this->modelId, 'id'), 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'payment' => ['nullable', 'min:2'],
                'iban' => ['nullable', 'min:2'],
                'bic' => ['nullable', 'min:2'],
                'notes' => ['nullable'],
                'contact' => ['nullable','min:6'],
                'contact_email' => ['nullable','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'anydesk_client_id' => ['required_with:anydesk_client_secret', Rule::unique(Company::class)->ignore($this->modelId, 'id')],
                'anydesk_client_secret' => ['required_with:anydesk_client_id'],
                'customer_type_id' => ['nullable', 'exists:' . CustomerType::class . ',id']
            ];
        }
        else {
            $rules = [
                'name' => ['required','min:2', 'unique:App\Models\Company'],
                'subdomain' => ['required','regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])?$/', 'unique:App\Models\Company','unique:App\Models\Subdomain'],
//                'logo' => ['nullable','image', 'max:1024'],  //mimes:jpg,png,jpeg
                'address' => ['nullable','min:6'],
                'zip' => ['nullable', 'min:2'],
                'city' => ['nullable','required_with:country'],
                'country' => ['required_with:city'],
                'email' => ['nullable', 'unique:App\Models\Company', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'payment' => ['nullable', 'min:2'],
                'iban' => ['nullable', 'min:2'],
                'bic' => ['nullable', 'min:2'],
                'notes' => ['nullable'],
                'contact' => ['nullable','min:6'],
                'contact_email' => ['nullable','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'anydesk_client_id' => ['required_with:anydesk_client_secret', Rule::unique(Company::class)],
                'anydesk_client_secret' => ['required_with:anydesk_client_id'],
                'customer_type_id' => ['nullable', 'exists:' . CustomerType::class . ',id']
            ];
        }
        return $rules;
    }

    public function getModelId($modelId)
    {
        $this->modelId = $modelId;
        $model      = $this->company;
        $this->name = $model->name;
        $this->subdomain = $model->subdomain;
        $this->address = $model->address;
        $this->zip = $model->zip;
        $this->city = $model->city;
        $this->country = $model->country;
        $this->email = $model->email;
        $this->payment = $model->payment;
        $this->iban = $model->iban;
        $this->bic = $model->bic;
        $this->notes = $model->notes;
        $this->contact = $model->contact;
        $this->contact_email = $model->contact_email;
        $this->anydesk_client_id = $model->anydesk_client_id;
        $this->anydesk_client_secret = $model->anydesk_client_secret;
        $this->customer_type_id = (!empty($model->customer_type_id) ? $model->customer_type_id : Company::DEFAULT_CUSTOMER_TYPE_ID);

        $this->prevName = $model->name;
        $this->prevSubdomain = $model->subdomain;
        $this->prevLogo = $model->logo;
        $this->prevAddress = $model->address;
        $this->prevZip = $model->zip;
        $this->prevCity = $model->city;
        $this->prevCountry = $model->country;
        $this->prevEmail = $model->email;
        $this->prevPayment = $model->payment;
        $this->prevIban = $model->iban;
        $this->prevBic = $model->bic;
        $this->prevNotes = $model->notes;
        $this->prevContact = $model->contact;
        $this->prevContactEmail = $model->contact_email;
        $this->prevCeamviewerClientId = $model->anydesk_client_id;
        $this->prevTeamviewerClientSecret = $model->anydesk_client_secret;
        $this->prevCustomerTypeId = $model->customer_type_id;
    }

    public function save($order)
    {
        $this->store();

        if ($order == 'new') {
            if ($this->toastrMsg) {
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Created!')]);
            }
        } else {
            if ($this->modelId) {
                if ($this->toastrMsg) {
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Updated!')]);
                }
            } else {
                if ($this->toastrMsg) {
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Created!')]);
                }
            }

            $this->emit('refreshParent');

            $this->emit('hideModal');
        }

        $this->cleanVars();
    }

    public function cancel()
    {
        $this->cleanVars();
        $this->modelId = '';
    }

    public function store()
    {

        if (empty($this->customer_type_id)) {
            $this->customer_type_id = null;
        }

        $formData = [
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'customer_type_id' => $this->customer_type_id,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'email' => $this->email,
            'payment' => $this->payment,
            'iban' => $this->iban,
            'bic' => $this->bic,
            'notes' => $this->notes,
            'contact' => $this->contact,
            'contact_email' => $this->contact_email
        ];

        if (!empty($this->anydesk_client_id)) {
            $formData['anydesk_client_id'] = $this->anydesk_client_id;
        }

        if (!empty($this->anydesk_client_secret)) {
            $formData['anydesk_client_secret'] = $this->anydesk_client_secret;
        }

        $validator = Validator::make($formData, $this->rules());

        if ($validator->fails()) {
            $errorsMsg = $validator->getMessageBag();
            if ($errorsMsg->has(['name']) || $errorsMsg->has(['subdomain']) || $errorsMsg->has(['logo'])) {
                $this->tab = 'basic';
            }
            elseif ($errorsMsg->has(['address']) || $errorsMsg->has(['zip']) || $errorsMsg->has(['city'])
                || $errorsMsg->has(['country']) || $errorsMsg->has(['email']) || $errorsMsg->has(['payment']) || $errorsMsg->has(['iban'])
                || $errorsMsg->has(['bic']) || $errorsMsg->has(['notes']) || $errorsMsg->has(['contact']) || $errorsMsg->has(['contact_email'])) {
                $this->tab = 'billing';
            } elseif ($errorsMsg->has(['anydesk_client_id']) || $errorsMsg->has(['anydesk_client_secret'])) {
                $this->tab = 'anydesk';
            }
            $this->dispatchBrowserEvent('focusErrorInput',['field' => array_key_first($errorsMsg->getMessages())]);
            $validator->validate();
        }

        $data = [];
        $validatedData = [];
        if ($this->modelId) {
            if ($this->name !== $this->prevName) {
                $data = array_merge($data, ['name' => $this->name]);
                $validatedData = array_merge($validatedData, [
                    'name' => ['required','min:2', Rule::unique('companies')->ignore($this->modelId, 'id')],
                ]);
            }
            if ($this->subdomain !== $this->prevSubdomain) {
                $data = array_merge($data, ['subdomain' => $this->subdomain]);
                $validatedData = array_merge($validatedData, [
                    'subdomain' => ['required','regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])?$/', Rule::unique('companies')->ignore($this->modelId, 'id')],
                ]);
            }
            if (!is_null($this->logo)) {
                $data = array_merge($data, ['logo' => $this->logo]);
                $validatedData = array_merge($validatedData, [
                    'logo' => ['image', 'max:1024'] //mimes:jpg,png,jpeg
                ]);
            }
            if ($this->address != $this->prevAddress) {
                $data = array_merge($data, ['address' => $this->address]);
                $validatedData = array_merge($validatedData, [
                    'address' => ['nullable', 'min:6']
                ]);
            }
            if ($this->zip !== $this->prevZip) {
                $data = array_merge($data, ['zip' => $this->zip]);
                $validatedData = array_merge($validatedData, [
                    'zip' => ['nullable', 'min:2']
                ]);
            }
            if ($this->city != $this->prevCity) {
                $data = array_merge($data, ['city' => $this->city]);
                $validatedData = array_merge($validatedData, [
                    'city' => ['nullable','required_with:country']
                ]);
            }
            if ($this->country != $this->prevCountry) {
                $data = array_merge($data, ['country' => $this->country]);
                $validatedData = array_merge($validatedData, [
                    'country' => ['required_with:city']
                ]);
            }
            if ($this->email != $this->prevEmail) {
                $data = array_merge($data, ['email' => $this->email]);
                $validatedData = array_merge($validatedData, [
                    'email' => ['nullable', Rule::unique('companies')->ignore($this->modelId, 'id'), 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/']
                ]);
            }
            if ($this->payment != $this->prevPayment) {
                $data = array_merge($data, ['payment' => $this->payment]);
                $validatedData = array_merge($validatedData, [
                    'payment' => ['nullable', 'min:2']
                ]);
            }
            if ($this->iban != $this->prevIban) {
                $data = array_merge($data, ['iban' => $this->iban]);
                $validatedData = array_merge($validatedData, [
                    'iban' => ['nullable', 'min:2']
                ]);
            }
            if ($this->bic != $this->prevBic) {
                $data = array_merge($data, ['bic' => $this->bic]);
                $validatedData = array_merge($validatedData, [
                    'bic' => ['nullable', 'min:2']
                ]);
            }
            if ($this->notes != $this->prevNotes) {
                $data = array_merge($data, ['notes' => $this->notes]);
                $validatedData = array_merge($validatedData, [
                    'notes' => ['nullable']
                ]);
            }
            if ($this->contact != $this->prevContact) {
                $data = array_merge($data, ['contact' => $this->contact]);
                $validatedData = array_merge($validatedData, [
                    'contact' => ['nullable','min:6']
                ]);
            }
            if ($this->contact_email != $this->prevContactEmail) {
                $data = array_merge($data, ['contact_email' => $this->contact_email]);
                $validatedData = array_merge($validatedData, [
                    'contact_email' => ['nullable','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/']
                ]);
            }
            if ($this->anydesk_client_id != $this->prevTeamviewerClientId) {
                $data = array_merge($data, ['anydesk_client_id' => $this->anydesk_client_id]);
                $validatedData = array_merge($validatedData, [
                    'anydesk_client_id' => ['required_with:anydesk_client_secret', Rule::unique(Company::class)->ignore($this->modelId, 'id')]
                ]);
            }
            if ($this->anydesk_client_secret != $this->prevTeamviewerClientSecret) {
                $data = array_merge($data, ['anydesk_client_secret' => $this->anydesk_client_secret]);
                $validatedData = array_merge($validatedData, [
                    'anydesk_client_secret' => ['required_with:anydesk_client_id']
                ]);
            }
            if ($this->customer_type_id != $this->prevCustomerTypeId) {
                $data = array_merge($data, ['customer_type_id' => $this->customer_type_id]);

                $validatedData = array_merge($validatedData, [
                    'customer_type_id' => ['nullable', 'exists:' . CustomerType::class . ',id']
                ]);
            }

            $validator = Validator::make($data, $validatedData)->validate();

            if (count($validator)) {
                $company = Company::find($this->modelId);
                if (!is_null($this->logo)) {
                    if (!empty($this->prevLogo)) {
//                        $path_profile = explode('/', $this->prevLogo);
                        $path_logo = 'public/' . str_replace('/storage/',"", $this->prevLogo);
//                        $builded_path = '/public/'.$path_profile[2].'/'.$path_profile[3].'/'.$path_profile[4].'/'.$path_profile[5];
                        $deleted = Storage::delete($path_logo);
                    }
                    $path = $this->handleImageIntervention($this->logo, $company->id);
//                    Tenancy::setTenant($company);
//                    $photo = $this->logo->store('/logo', 'tenant');
//                    $path = 'tenants/' . Tenancy::getTenant()->getTenantKey() . '/' . $photo;
                    $url = Storage::url($path);
                    $validator['logo'] = $url;
                }

                $company->update($validator);
                $this->toastrMsg = true;
            }
        }
        else
        {
            if (!is_null($this->logo))
                $validateImage = Validator::make(['logo' => $this->logo],['logo' => ['nullable','image', 'max:1024']])->validate();

            $company = Company::create($validator->validated());
            if (!is_null($this->logo)) {

//                Tenancy::setTenant($company);
//                $photo = $this->logo->store('/logo', 'tenant');
//                $path = 'tenants/'.Tenancy::getTenant()->getTenantKey().'/'.$photo;
                $path = $this->handleImageIntervention($this->logo, $company->id);
                $url = Storage::url($path);
                $company->logo = $url;
                $company->save();
            }
            $this->toastrMsg = true;
        }
        Tenancy::setTenant(null);
        Tenancy::setIdentified(false);
    }

    public function handleImageIntervention($file, $companyId)
    {
        $company = Company::whereId($companyId)->first();
        $image       = $file;
        $filename    = $image->getClientOriginalName();

        $path = 'tenants/' . $company->id . '/logo/tmp/' . $filename;

        $validator = Validator::make(['logo' => $image],['logo' => ['image']])->validate();

        $files = Storage::allFiles('public/tenants/' . $company->id . '/logo/tmp');
        if ($files) {
            foreach ($files as $deleteItem)
                Storage::delete($deleteItem);
        }

        Tenancy::setTenant($company);
        Storage::disk('tenant')->putFileAs('/logo/tmp/', $image, $filename);

//        $image = Image::make($image->getRealPath());

        $image = Image::make('storage/' . $path);
        $width = $image->width();
        $height = $image->height();
        $type = $image->mime();

        if ($type != 'image/svg+xml' && APP_EDITION == 'billonthego') {
            if ($width < 600 && $height < 600) {
                $image_intervention = Image::canvas(600, 600, $type != 'image/png' ? '#FFFFFF' : null);
            } elseif ($width < 600) {
                $image_intervention = Image::canvas(600, $height, $type != 'image/png' ? '#FFFFFF' : null);
            } elseif ($height < 600) {
                $image_intervention = Image::canvas($width, 600, $type != 'image/png' ? '#FFFFFF' : null);
            }
            $save_path = 'storage/tenants/' . $company->id . '/logo';
            if (isset($image_intervention)) {
                $image_intervention->insert('storage/' . $path,'center');
                if (!file_exists($save_path)) {
                    mkdir($save_path, 666, true);
                }
                $image_intervention->save($save_path . '/' . $filename);
            } elseif ($image) {
                $image->save($save_path . '/' . $filename);
            }
            if ($image || $image_intervention)
                return 'tenants/' . $company->id . '/logo/' . $filename;
        } else {
            $filename = $file->store('/logo', 'tenant');
            return 'tenants/' . $company->id . '/' . $filename;
        }
        return  null;
    }

    public function onResetButton()
    {
        if (!is_null($this->prevLogo)) {
            if (!is_null($this->logo)) {
                $this->logo = null;
            } else {
                if (!empty($this->company)) {
                    $this->emit('showModal', 'show-company-modals', $this->company);
                } else {
                    $this->emit('showModal', 'show-company-modals');
                }
            }
        } else {
            $this->logo = null;
        }

        $this->resetValidation();
    }

    public function deletePhoto()
    {
        $path_profile = 'public/' . str_replace('/storage/', "", $this->prevLogo);

        // $builded_path = public_path($path_profile[2].'/'.$path_profile[3].'/'.$path_profile[4].'/'.$path_profile[5]);

        $deleted = Storage::delete($path_profile);

        Company::find($this->modelId)->update(['logo' => null]);

        $this->cleanLogoVars();

        // $this->dispatchBrowserEvent('closeDeleteLogoModal');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Company Logo Deleted!')]);
    }

    public function cleanVars()
    {
        $this->modelId = null;

        $this->name = null;
        $this->subdomain = null;
        $this->logo = null;
        $this->address = null;
        $this->zip = null;
        $this->city = null;
        $this->country = null;
        $this->email = null;
        $this->payment = null;
        $this->iban = null;
        $this->bic = null;
        $this->notes = null;
        $this->contact = null;
        $this->contact_email = null;
        $this->anydesk_client_id = null;
        $this->anydesk_client_secret = null;
        $this->customer_type_id = null;

        $this->prevName = null;
        $this->prevSubdomain = null;
        $this->prevLogo = null;
        $this->prevAddress = null;
        $this->prevZip = null;
        $this->prevCity = null;
        $this->prevCountry = null;
        $this->prevEmail = null;
        $this->prevPayment = null;
        $this->prevIban = null;
        $this->prevBic = null;
        $this->prevNotes = null;
        $this->prevContact = null;
        $this->prevContactEmail = null;
        $this->prevCustomerTypeId = null;
        $this->reset('toastrMsg');

    }

    public function cleanLogoVars() {
        $this->logo = null;
        $this->prevLogo = null;
    }

    public function updateField($params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
