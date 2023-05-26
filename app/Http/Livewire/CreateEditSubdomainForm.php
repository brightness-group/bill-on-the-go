<?php

namespace App\Http\Livewire;

use App\Models\Subdomain;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Nullable;

class CreateEditSubdomainForm extends Component
{
    public $subdomain, $description, $target, $modelId;

    protected $listeners = [
        'getSubdomainModelId',
        'selectedCompany',
        'forcedCloseSubdomainModal'
    ];

    public function render()
    {
        return view('livewire.create-edit-subdomain-form');
    }

    public function updated($subdomain)
    {
        if ($this->modelId) {
            $this->validateOnly($subdomain, [
                'subdomain' => ['required','min:3',
                    Rule::unique('subdomains')->ignore($this->modelId, 'id'),
                    'unique:App\Models\Company']
            ]);
        }
        else {
            $this->validateOnly($subdomain, [
                'subdomain' => ['required', 'min:3', 'unique:subdomains','unique:App\Models\Company']
            ]);
        }
    }

    public function getSubdomainModelId($modelId)
    {
        $this->modelId = $modelId;
        $model = Subdomain::find($this->modelId);
        $this->subdomain = $model->subdomain;
        $this->target = $model->target;
        $this->description = $model->description;

    }

    public function rules(){

        return [
            'subdomain' => ['required','regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])?$/',
                Rule::unique('subdomains')->ignore($this->modelId, 'id'),
                'unique:App\Models\Company'],
            'description' => ['nullable'],
            'target' => ['nullable']
        ];

    }

    public function store()
    {

        $validator = Validator::make(
            [
                'subdomain' => $this->subdomain,
                'description' => $this->description,
                'target' => $this->target,
            ]
            , $this->rules())->validate();

//        dd($validator);

        //Speichern

        if ($this->modelId) {
            //Updaten
            $obj = Subdomain::find($this->modelId);
            $obj->update($validator);

        } else {
            //Create
            $newsubdomain = Subdomain::create($validator);
        }
        //CLosne
        $this->closeFormModal();

    }

    public function closeFormModal()
    {
        $this->forcedCloseSubdomainModal();
        $this->dispatchBrowserEvent('closeFormSubdomainModal');
    }

    public function cleanVars()
    {
        $this->subdomain = '';
        $this->description = '';
        $this->target = '';
        $this->modelId = '';
    }

    public function forcedCloseSubdomainModal()
    {
        $this->resetValidation();
        $this->cleanVars();
        $this->emit('clearSelectedItem');
    }
}
