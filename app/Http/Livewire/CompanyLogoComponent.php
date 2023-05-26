<?php

namespace App\Http\Livewire;

use App\Helpers\CoreHelpers;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Livewire\WithFileUploads;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class CompanyLogoComponent extends Component
{
    use WithFileUploads;

    public $company, $logo, $prevLogo,$rectangleLogo, $prevRectangleLogo;

    protected $listeners = [
        'deletePhoto'
    ];

    public function mount()
    {
        $this->company = Tenancy::getTenant();
        $this->getModel();

        if ($this->company->logo == "") {
            $this->company->update(['logo' => null]);
        }
        if ($this->company->rectangle_logo == "") {
            $this->company->update(['rectangle_logo' => null]);
        }
    }

    public function getModel()
    {
        $this->prevLogo = $this->company->logo;
        $this->prevRectangleLogo = $this->company->rectangle_logo;
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'logo') {
            $this->validateOnly($propertyName, [
                'logo' => ['nullable', 'image', 'max:1024']
            ]);
        } else if ($propertyName == 'rectangle_logo') {
            $this->validateOnly($propertyName, [
                'rectangle_logo' => ['nullable', 'image', 'max:1024']
            ]);
        }
    }

    public function save()
    {
        $data = [];
        $validatedData = [];
        if (!is_null($this->logo)) {
            $data = array_merge($data, ['logo' => $this->logo]);
            $validatedData = array_merge($validatedData, [
                'logo' => ['image', 'max:1024'] //mimes:jpg,png,jpeg
            ]);
        }

        if (!is_null($this->rectangleLogo)) {
            $data = array_merge($data, ['rectangle_logo' => $this->rectangleLogo]);
            $validatedData = array_merge($validatedData, [
                'rectangle_logo' => ['image', 'max:1024'] //mimes:jpg,png,jpeg
            ]);
        }

        $validator = Validator::make($data, $validatedData)->validate();
        if (count($validator)) {

            // square & auth logo
            if (!is_null($this->logo)) {
                if (!is_null($this->prevLogo)) {
                    $path_logo = 'public/' . str_replace('/storage/', "", $this->prevLogo);

                    if (Str::contains($path_logo, 'tenants')) {
                        $path_auth_logo = CoreHelpers::getFileUrl($path_logo, 'auth');
                        Storage::delete($path_auth_logo);
                        $path_square_logo = CoreHelpers::getFileUrl($path_logo, 'square');
                        Storage::delete($path_square_logo);
                    }
                    Storage::delete($path_logo);
                }
                $path = $this->handleImageIntervention($this->logo, $this->company->id,'square');
                $validator['logo'] = Storage::url($path);
            }

            // rectangle_logo
            if (!is_null($this->rectangleLogo)) {
                if (!is_null($this->prevRectangleLogo)) {
                    $path_rectangle_logo_db = 'public/' . str_replace('/storage/', "", $this->prevRectangleLogo);
                    if (Str::contains($path_rectangle_logo_db, 'tenants')) {
                        $path_rectangle_logo = CoreHelpers::getFileUrl($path_rectangle_logo_db, 'rectangle');
                        Storage::delete($path_rectangle_logo);

                        $path_tmp_logo = CoreHelpers::getFileUrl($path_rectangle_logo_db, 'tmp');
                        Storage::delete($path_tmp_logo);
                    }
                    Storage::delete($path_rectangle_logo_db);
                }
                $path = $this->handleImageIntervention($this->rectangleLogo, $this->company->id,'rectangle');
                $validator['rectangle_logo'] = Storage::url($path);
            }
            $this->company->update($validator);
            $this->cleanLogoVars();
            $this->getModel();
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Logo Saved!')]);
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

    public function handleImageIntervention($file, $companyId, $logoType)
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
        $type = $image->mime();

        if ($type != 'image/svg+xml') {
            if ($logoType == 'square') {
                // save image for auth pages logo
                $save_path_for_square = 'storage/tenants/' . $company->id . '/logo/square';
                $save_path_for_auth = 'storage/tenants/' . $company->id . '/logo/auth';
                $this->resizeImage($company->id, $file, 100, 100, $save_path_for_square, '182235');
                $this->resizeImage($company->id, $file, 400, 400, $save_path_for_auth);
                return 'tenants/' . $company->id . '/logo/' . $filename;
            } elseif ($logoType == 'rectangle') {
                // save image for full sidebar menu logo
                $save_path_for_rectangle_logo = 'storage/tenants/' . $company->id . '/logo/rectangle';
                $this->resizeImage($company->id, $file, 399, 60, $save_path_for_rectangle_logo, '182235');
                return 'tenants/' . $company->id . '/logo/' . $filename;
            }
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
                $this->dispatchBrowserEvent('openDeleteLogoModal', ['logo_type' => 'square']);
            }
        }
        else {
            $this->logo = null;
        }
        $this->resetValidation();
    }

    public function onResetButtonForRectangleLogo()
    {
        if (!is_null($this->prevRectangleLogo)) {
            if (!is_null($this->rectangleLogo)) {
                $this->rectangleLogo = null;
            }
            else {
                $this->dispatchBrowserEvent('openDeleteLogoModal', ['logo_type' => 'rectangle']);
            }
        }
        else {
            $this->rectangleLogo = null;
        }
        $this->resetValidation();
    }

    public function deletePhoto($type)
    {
        if($type == 'square'){
            // auth
            $auth_logo_path = explode('/', CoreHelpers::getFileUrl($this->prevLogo,'auth'));
            Storage::delete('/public/' . $auth_logo_path[2] . '/' . $this->company->id . '/logo/' . $auth_logo_path[5] . '/' . $auth_logo_path[6]);

            // square
            $square_logo_path = explode('/', CoreHelpers::getFileUrl($this->prevLogo,'square'));
            Storage::delete('/public/' . $square_logo_path[2] . '/' . $this->company->id . '/logo/' . $square_logo_path[5]. '/' . $square_logo_path[6]);
            $this->company->update(['logo' => null]);
        }elseif($type == 'rectangle') {
            // rectangle
            $rectangle_logo_path = explode('/', CoreHelpers::getFileUrl($this->prevRectangleLogo,'rectangle'));
            Storage::delete('/public/' . $rectangle_logo_path[2] . '/' . $this->company->id . '/logo/' . $rectangle_logo_path[5]. '/' . $rectangle_logo_path[6]);

            // temp
            $tmp_logo_path = explode('/', CoreHelpers::getFileUrl($this->prevRectangleLogo,'tmp'));
            Storage::delete('/public/' . $tmp_logo_path[2] . '/' . $this->company->id . '/logo/' . $tmp_logo_path[5]. '/' . $tmp_logo_path[6]);
        }
        $this->cleanLogoVars();
        $this->dispatchBrowserEvent('closeDeleteLogoModal');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Company Logo Deleted!')]);
    }

    public function cleanLogoVars() {
        $this->logo = null;
        $this->prevLogo = null;
        $this->rectangleLogo = null;
        $this->prevRectangleLogo = null;
    }

    public function closeDeletePhotoModal()
    {
        $this->dispatchBrowserEvent('closeDeleteLogoModal');
    }
}
