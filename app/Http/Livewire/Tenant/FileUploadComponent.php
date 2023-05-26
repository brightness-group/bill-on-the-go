<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\File\File;
use App\Models\Tenant\File\FileConnection;
use App\Models\Tenant\SharedUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Tenancy\Facades\Tenancy;
use Exception;

class FileUploadComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $bulk = 50;

    public $file = null;
    public $filepond;

    public $show_stored = false;
    public $buttonIsVisible = false;

    public $file_pond_error;

    protected $listeners = [
        'refresh' => '$refresh',
        'processedFile',
        'resetPond',
        'clearErrorBag'
    ];

    public function mount()
    {
        $this->removeProcessFileUpload();
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.tenant.file-upload-component')
            ->extends('tenant.theme-new.layouts.layoutMaster')
            ->section('content');
    }

    public function updatedFilepond($value)
    {
        if (!is_null($value))
            $this->fileUploaded();
        else {
            $this->removeProcessFileUpload();
            $this->clearVars();
        }
    }

    public function loadData()
    {
        $this->file = Auth::user()->file()->exists() ? Auth::user()->file()->latest()->first() : null;
    }

    public function validateFileFormat($model)
    {
        $fp = fopen(Storage::path('public/' . $model->path_to_file), "r");
        $flag = [];
        while(!feof($fp)) {
            $line = fgets($fp);
            $list = explode('/', preg_replace('/\s+/', '/', str_replace(array('{', '}'), '', $line)));

            if (count($list) > 6) {
                if (array_key_exists(1,$list) && array_key_exists(2,$list) && array_key_exists(3,$list) && array_key_exists(4,$list)) {
                    if (Carbon::createFromFormat('d-m-Y H:i:s', $list[1] . ' ' . $list[2]) !== false) {
                        $start_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[1] . ' ' . $list[2]);
                        $flag['start_date'] = true;
                    }
                    if (Carbon::createFromFormat('d-m-Y H:i:s', $list[3] . ' ' . $list[4]) !== false) {
                        $end_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[3] . ' ' . $list[4]);
                        $flag['end_date'] = true;
                    }
                }
            }
        }
        if (array_values($flag) != true) {
            throw new Exception(__('locale.Something wrong happen with your request.'));
        }
        return true;
    }

    public function fileUploaded()
    {
//        if (!$this->file) {
            $pathToFile = Storage::disk('tenant')->putFileAs('/connection/user/' . Auth::id() . '/file', $this->filepond, $this->filepond->getClientOriginalName());
            $this->file = File::create([
                'original_name' => $this->filepond->getClientOriginalName(),
                'path_to_file' => 'tenants/' . Tenancy::getTenant()->getTenantKey() . '/' . $pathToFile,
                'user_id' => Auth::id(),
            ]);
//        }
//        else {
//            Storage::delete('public/' . $this->file->path_to_file);
//            $pathToFile = Storage::disk('tenant')->putFileAs('/connection/user/' . Auth::id() . '/file/', $this->filepond, $this->filepond->getClientOriginalName());
//            $this->file->update([
//                'original_name' => $this->filepond->getClientOriginalName(),
//                'path_to_file' => 'tenants/' . Tenancy::getTenant()->getTenantKey() . '/' . $pathToFile,
//                'user_id' => Auth::id(),
//            ]);
//        }
        if ($this->file)
//            $this->file->refresh();
            try {
                $this->validateFileFormat($this->file);
            } catch (Exception $e) {
                if ($e) {
                    $this->dispatchBrowserEvent('uploadErrorInFile',['code' => $e->getCode(), 'message' => $e->getMessage()]);
                    $validate = Validator::make(['file_pond_error' => $this->file_pond_error],['file_pond_error' => ['required']],[
                        'required' => __('locale.wrong file format')
                    ]);
                    if ($validate->fails()) {
                        Storage::delete('public/' . $this->file->path_to_file);
                        $this->file->delete();
                        $validate->validate();
                    }
                }
            }
    }

    public function processedFile()
    {
        $file = $this->file;
        $flag = false;
        $created = false;
        if ($file && Storage::exists('public/' . $file->path_to_file)) {
            $fp = fopen(Storage::path('public/' . $file->path_to_file), "r");
            while(!feof($fp)){
                $line = fgets($fp);
                $list = explode('/',preg_replace('/\s+/', '/', str_replace(array('{','}'),'',$line)));
                if (count($list) > 6) {
                    $id = $list[7];
                    $start_date = Carbon::createFromFormat('d-m-Y H:i:s',$list[1] . ' ' . $list[2]);
                    $end_date = Carbon::createFromFormat('d-m-Y H:i:s',$list[3] . ' ' . $list[4]);
//                    $user_short_name = $list[5];
//                    $user = SharedUser::query()->where('name','LIKE','%' . $user_short_name . '%')->get();
//                    $user_id = null;
//                    $username = null;
//                    if (count($user) == 1) {
//                        $user_id = $user[0]->id;
//                        $username = $user[0]->name;
//                    }
                    if (! ConnectionReport::query()->whereKey($id)->exists()) {
                        ConnectionReport::create([
                            'id' => $id,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
//                            'userid' => $user_id,
//                            'username' => $username,
                        ]);
                        $flag = true;
                        $created = true;
                    }
                }
            }
            fclose($fp);
        }
        if($created){
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Connections Created Successfully!')]);
            $this->file = null;
        }else{
            $this->dispatchBrowserEvent('showToastrWarning', ['message' => __('locale.These Connections have already been created!')]);
        }
    }

    public function removeProcessFileUpload()
    {
        $file = Auth::user()->file()->exists() ? Auth::user()->file()->latest()->first() : null;
        if ($file && !$file->uploaded) {
            Storage::delete('public/' . $file->path_to_file);
            $file->delete();
        }
    }

    public function cancel()
    {
        $this->removeProcessFileUpload();
        $this->clearVars();
    }

    public function clearVars()
    {
        $this->filepond = null;
        $this->file = null;
        $this->reset(['buttonIsVisible','bulk']);
        $this->resetErrorBag();
        $this->resetPond();
    }

    public function clearErrorBag()
    {
        $this->resetValidation(['file_pond_error']);
    }

    public function resetPond()
    {
        $this->removeProcessFileUpload();
        $this->dispatchBrowserEvent('resetPond');
    }
}
