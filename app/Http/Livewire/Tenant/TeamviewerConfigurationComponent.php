<?php

namespace App\Http\Livewire\Tenant;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Tenancy\Facades\Tenancy;
use Illuminate\Validation\Rule;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class TeamviewerConfigurationComponent extends Component
{
    public $tenant;
    public $anydesk_client_id;
    public $anydesk_client_secret;
    public $showManual = false;
    protected $listeners = ['checkForTeamviewerModelShow'];

    public function mount() {
        $this->tenant = Tenancy::getTenant();

        if (!empty($this->tenant)) {
            $this->anydesk_client_id     = $this->tenant->anydesk_client_id;
            $this->anydesk_client_secret = $this->tenant->anydesk_client_secret;
        }
    }

    public function checkForTeamviewerModelShow()
    {
        if ($this->showManual) {
            $this->dispatchBrowserEvent('showTeamviewerManual');
        }
    }

    public function render()
    {
        $this->showManual = request()->get('showManual', false);

        return view('livewire.tenant.anydesk-configuration-component');
    }

    public function save()
    {
        if (!empty($this->tenant)) {

            // Set unique for live.
            $unique = [];
            $appEnv = strtolower(config('app.env', ''));

            if ($appEnv == 'prod' || $appEnv == 'production' || $appEnv == 'live') {
                $unique[] = Rule::unique(Company::class)->ignore($this->tenant->id, 'id');
            }

            $formData = [
                'anydesk_client_id' => $this->anydesk_client_id,
                'anydesk_client_secret' => $this->anydesk_client_secret
            ];

            $validatedData = Validator::make($formData, [
                'anydesk_client_id' => array_merge(['required'], $unique),
                'anydesk_client_secret' => ['required']
            ])->validate();

            $this->tenant->update($validatedData);

            $this->resetValidation();

            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Updated!')]);
            $this->emitTo('tenant.test-anydesk-component','reload');
        } else {
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Something is wrong with your request. Please contact your administrator!')]);
        }
    }
}
