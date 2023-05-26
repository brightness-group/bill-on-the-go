<?php

namespace App\Http\Livewire\Tenant;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Helpers\CoreHelpers;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Tenancy\Facades\Tenancy;
use Intervention\Image\Facades\Image;

class ShowAccountSettingComponent extends Component
{
    public $tariffSelectedColor;

    public $fromCallback;

    use WithFileUploads;

    public string $tab = 'basic';
    public string $contentShow = 'basic';
    public \Tenancy\Identification\Contracts\Tenant|null $company;
    public string $search = '';

    public $name, $logo, $address, $zip, $city, $country, $phone, $email, $contact, $contact_email, $website, $notes;
    public $billing_address, $payment, $iban, $bic, $tax_number;
    public $prevName, $prevLogo, $prevAddress, $prevZip, $prevCity, $prevCountry, $prevPhone, $prevEmail, $prevContact, $prevContactEmail, $prevWebsite, $prevNotes;
    public $prevBillingAddress, $prevPayment, $prevIban, $prevBic, $prevTaxNumber, $prevTeamviewerClientId, $prevTeamviewerClientSecret;

    public $isAdminRole = false;

    protected $listeners = [
        'searchUpdate',
        'updateField'
    ];

    public function hydrate()
    {
        $this->emit('inputMasksHydrate');
    }

    public function dehydrate()
    {
        $this->emit('parentComponentErrorBag', $this->getErrorBag());
    }

    public function mount()
    {
        $this->company = Tenancy::getTenant();

        if ($this->company->logo == "") {
            $this->company->update(['logo' => null]);
        }

        $this->getModel();

        // Check and append search values.
        $search = request()->get('search', null);
        if (!empty($search)) {
            $this->search = $search;
        }

        if (request()->has('contentShow') && in_array(request()->get('contentShow', ''), ['basic', 'billing', 'user-management'])) {
            $this->contentShow = request()->get('contentShow', false);
        } else {
            $this->contentShow = CoreHelpers::getPreviousState('dashboard','tabContent', $this->contentShow);
        }

        $this->isAdminRole = auth()->user()->hasRole('Admin');
    }

    public function getModel()
    {
        $this->name = $this->company->name;
        $this->address = $this->company->address;
        $this->zip = $this->company->zip;
        $this->city = $this->company->city;
        $this->country = $this->company->country;
        $this->phone = $this->company->phone;
        $this->email = $this->company->email;
        $this->contact = $this->company->contact;
        $this->contact_email = $this->company->contact_email;
        $this->website = $this->company->website;
        $this->notes = $this->company->notes;

        $this->billing_address = $this->company->billing_address;
        $this->payment = $this->company->payment;
        $this->iban = $this->company->iban;
        $this->bic = $this->company->bic;
        $this->tax_number = $this->company->tax_number;

        if ($this->iban || $this->bic) {
            $this->dispatchBrowserEvent('loadIbanBicInputs',['iban'=>$this->iban,'bic'=>$this->bic]);
        }

        $this->prevName = $this->company->name;
        $this->prevLogo = $this->company->logo;
        $this->prevAddress = $this->company->address;
        $this->prevZip = $this->company->zip;
        $this->prevCity = $this->company->city;
        $this->prevCountry = $this->company->country;
        $this->prevPhone = $this->company->phone;
        $this->prevEmail = $this->company->email;
        $this->prevContact = $this->company->contact;
        $this->prevContactEmail = $this->company->contact_email;
        $this->prevWebsite = $this->company->website;
        $this->prevNotes = $this->company->notes;

        $this->prevBillingAddress = $this->company->billing_address;
        $this->prevPayment = $this->company->payment;
        $this->prevIban = $this->company->iban;
        $this->prevBic = $this->company->bic;
        $this->prevTaxNumber = $this->company->tax_number;
    }

    public function rules()
    {
         return [
                'name' => ['required','min:2', Rule::unique('companies')->ignore($this->company->id, 'id')],
//                'logo' => ['nullable','image', 'max:1024'],  //mimes:jpg,png,jpeg
                'address' => ['nullable', 'min:6'],
                'zip' => ['nullable', 'min:2'],
                'city' => ['nullable','required_with:country'],
                'country' => ['required_with:city'],
                'phone' => ['nullable'],
                'email' => ['nullable', Rule::unique('companies')->ignore($this->company->id, 'id'), 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'contact' => ['nullable','min:6'],
                'contact_email' => ['nullable','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/'],
                'website' => ['nullable','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],
                'notes' => ['nullable'],

                'billing_address' => ['nullable','min:2'],
                'payment' => ['nullable', 'min:2'],
                'iban' => ['nullable', 'min:2'],
                'bic' => ['nullable', 'min:2'],
                'tax_number' => ['nullable', 'min:2']
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules() + [
                'logo' => ['nullable','image', 'max:1024']
            ]);
    }

    public function redirectToUsersTab()
    {
        session()->push('redirectUsersTab',true);
        return redirect()->route('account.settings');
    }

    public function save()
    {
        $formData = [
            'name' => $this->name,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'contact' => $this->contact,
            'contact_email' => $this->contact_email,
            'website' => $this->website,
            'notes' => $this->notes,
            'billing_address' => $this->billing_address,
            'payment' => $this->payment,
            'iban' => $this->iban,
            'bic' => $this->bic,
            'tax_number' => $this->tax_number
        ];

        $validator = Validator::make($formData, $this->rules());

        if ($validator->fails()) {
            $errorsMsg = $validator->getMessageBag();
            if ($errorsMsg->has(['name']) || $errorsMsg->has(['logo']) || $errorsMsg->has(['address']) || $errorsMsg->has(['zip']) ||
                $errorsMsg->has(['city']) || $errorsMsg->has(['country']) || $errorsMsg->has(['phone']) || $errorsMsg->has(['email']) ||
                $errorsMsg->has(['contact']) || $errorsMsg->has(['contact_email']) || $errorsMsg->has(['website']) || $errorsMsg->has(['notes'])) {
                $this->tab = 'basic';
            }
            elseif ($errorsMsg->has(['billing_address']) || $errorsMsg->has(['payment']) || $errorsMsg->has(['iban']) || $errorsMsg->has(['bic']) ||
                    $errorsMsg->has(['tax_number'])) {
                $this->tab = 'billing';
            }
            $this->dispatchBrowserEvent('focusErrorInput',['field' => array_key_first($errorsMsg->getMessages())]);
            $validator->validate();
        }

        $data = [];
        $validatedData = [];
        if ($this->name !== $this->prevName) {
            $data = array_merge($data, ['name' => $this->name]);
            $validatedData = array_merge($validatedData, [
                'name' => ['required','min:2', Rule::unique('companies')->ignore($this->company->id, 'id')],
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
                'email' => ['nullable', Rule::unique('companies')->ignore($this->company->id, 'id'), 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/']
            ]);
        }
        if ($this->phone != $this->prevPhone) {
            $data = array_merge($data, ['phone' => $this->phone]);
            $validatedData = array_merge($validatedData, [
                'phone' => ['nullable']
            ]);
        }
        if ($this->website != $this->prevWebsite) {
            $data = array_merge($data, ['website' => $this->website]);
            $validatedData = array_merge($validatedData, [
                'website' => ['nullable','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/']
            ]);
        }
        if ($this->notes != $this->prevNotes) {
            $data = array_merge($data, ['notes' => $this->notes]);
            $validatedData = array_merge($validatedData, [
                'notes' => ['nullable']
            ]);
        }
        if ($this->billing_address != $this->prevBillingAddress) {
            $data = array_merge($data, ['billing_address' => $this->billing_address]);
            $validatedData = array_merge($validatedData, [
                'billing_address' => ['nullable','min:2']
            ]);
        }
        if ($this->payment != $this->prevPayment) {
            $data = array_merge($data, ['payment' => $this->payment]);
            $validatedData = array_merge($validatedData, [
                'payment' => ['nullable', 'min:2']
            ]);
        }
        if ($this->tax_number != $this->prevTaxNumber) {
            $data = array_merge($data, ['tax_number' => $this->tax_number]);
            $validatedData = array_merge($validatedData, [
                'tax_number' => ['nullable', 'min:2']
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

        $validator = Validator::make($data, $validatedData)->validate();

        if (count($validator)) {
            if (!is_null($this->logo)) {
                if (!is_null($this->prevLogo)) {
//                    $path_profile = explode('/', $this->prevLogo);
                    $path_logo = 'public/' . str_replace('/storage/',"", $this->prevLogo);
//                    $builded_path = '/public/'.$path_profile[2].'/'.$path_profile[3].'/'.$path_profile[4].'/'.$path_profile[5];

                    if (Str::contains($path_logo,'tenants')) {
                        $path_auth_logo = CoreHelpers::getFileUrl($path_logo,'auth');
                        Storage::delete($path_auth_logo);
                    }
                    Storage::delete($path_logo);
                }
                $path = $this->handleImageIntervention($this->logo, $this->company->id);
//                    Tenancy::setTenant($company);
//                    $photo = $this->logo->store('/logo', 'tenant');
//                    $path = 'tenants/' . Tenancy::getTenant()->getTenantKey() . '/' . $photo;
                $url = Storage::url($path);
                $validator['logo'] = $url;
            }
            $this->company->update($validator);
            $this->tab = 'basic';
            $this->dispatchBrowserEvent('focusErrorInput',['field' => 'name']);
            $this->cleanLogoVars();
            $this->getModel();
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Updated!')]);
        }
    }

    public function resizeImage($companyId, $file, $desiredWidth, $desiredHeight, $pathToSaveImage, $bgColor = 'ffffff'): void
    {
        $image = $file;
        $filename = $image->getClientOriginalName();

        // assuming you have an uploaded image file in $file variable
        $image = Image::make($file);

        $originalWidth = $image->getWidth();
        $originalHeight = $image->getHeight();
        $type = $image->mime();

        if ($originalWidth < $desiredWidth && $originalHeight < $desiredHeight) {
            $image_intervention = Image::canvas($desiredWidth, $desiredHeight, $type != 'image/png' ? $bgColor : null);
        } elseif ($originalWidth < $desiredWidth) {
            $image_intervention = Image::canvas($desiredWidth, $originalHeight, $type != 'image/png' ? $bgColor : null);
        } elseif ($originalHeight < $desiredHeight) {
            $image_intervention = Image::canvas($originalWidth, $desiredHeight, $type != 'image/png' ? $bgColor : null);
        } else {
            // Resize the image to fit within the specified dimensions
            $image->fit($desiredWidth, $desiredHeight, function ($constraint) {
                $constraint->upsize(); // prevent stretching
                $constraint->aspectRatio(); // maintain aspect ratio
            });
        }

        // create directory with permissions if specified directory not present.
        if (!file_exists($pathToSaveImage)) {
            mkdir($pathToSaveImage, 0755);
        }
        $temp_path = 'tenants/' . $companyId . '/logo/tmp/' . $filename;
        // save image accordingly
        if (isset($image_intervention)) {
            $image_intervention->insert('storage/' . $temp_path, 'center');
            $image_intervention->save($pathToSaveImage . '/' . $filename);
        } else {
            $image->save($pathToSaveImage . '/' . $filename);
        }
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

        $image = Image::make('storage/' . $path);
        $originalWidth = $image->width();
        $originalheight = $image->height();
        $type = $image->mime();

        if ($type != 'image/svg+xml') {

            // save image for sidebar menu logo
            $save_path_for_logo = 'storage/tenants/' . $company->id . '/logo';
            $this->resizeImage($company->id, $file, 266, 40, $save_path_for_logo, '182235');

            // save image for auth pages logo
            $save_path_for_auth = 'storage/tenants/' . $company->id . '/logo/auth';
            $this->resizeImage($company->id, $file, 352, 272, $save_path_for_auth);
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
            }
            else {
                $this->dispatchBrowserEvent('openDeleteLogoModal');
            }
        }
        else {
            $this->logo = null;
        }
        $this->resetValidation();
    }

    public function deletePhoto()
    {
        $path_profile = 'public/' . str_replace('/storage/',"", $this->prevLogo);
//        $build_path = '/public/'.$path_profile[2].'/'.$path_profile[3].'/'.$path_profile[4].'/'.$path_profile[5];
        $deleted = Storage::delete($path_profile);
        $this->company->update(['logo' => null]);
        $this->cleanLogoVars();
        $this->dispatchBrowserEvent('closeDeleteLogoModal');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Company Logo Deleted!')]);
    }

    public function cleanLogoVars() {
        $this->logo = null;
        $this->prevLogo = null;
    }

    public function closeDeletePhotoModal()
    {
        $this->dispatchBrowserEvent('closeDeleteLogoModal');
    }

    public function searchUpdate($search)
    {
        $this->search = $search;

        $this->dispatchBrowserEvent('searchUpdate', ['search' => $this->search]);
    }

    public function updateField($params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    public function updatedContentShow()
    {
        CoreHelpers::setState('dashboard','tabContent', $this->contentShow);
    }

    public function render()
    {
        $container = 'container';

        $this->fromCallback = request()->get('fromCallback', false);

        return view('livewire.tenant.show-account-setting-component')
            ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
            ->section('content');
    }

    public function updatedTariffSelectedColor($value)
    {
        $this->emit('changeColor', $value);
    }
}
