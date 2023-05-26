<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Company;
use App\Services\RetrieveDataFromAPI;
use Livewire\Component;
use Livewire\WithPagination;
use Tenancy\Facades\Tenancy;

class TestTeamviewerComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';

    public $connected = false;
    public $access_token = '';
    public $refreshToken = '';
    public $alertExpireToken = false;
    public $access_token_expire = false;

    public $showToastr = '';

    protected $listeners = [
        'checkForSessionToastr',
        'retrieveFromAPIEmitedEvent',
        'pingAndRefreshTVConnection',
        'refresh' => '$refresh',
        'reload',
    ];

    public function userAccessToken()
    {
        $this->access_token = Tenancy::getTenant()->anydesk_access_token;
        $this->refreshToken = Tenancy::getTenant()->anydesk_refresh_token;

        $this->access_token = Tenancy::getTenant()->anydesk_access_token;
        $this->refreshToken = Tenancy::getTenant()->anydesk_refresh_token;

        if (!empty($this->access_token)) {
            $this->access_token_expire = (Tenancy::getTenant()->anydesk_access_token_for_expire_check)->diffInHours(now()) >= 24;
        }

        if (!empty($this->access_token) && !$this->access_token_expire) {
            // Add ping
            $ping = RetrieveDataFromAPI::ping($this->access_token);

            $this->connected = $ping;

            $this->reset(['alertExpireToken']);
        } elseif ($this->access_token_expire) {
            $this->alertExpireToken = true;

            $this->reset(['connected', 'access_token']);
        } else {
            $this->reset(['connected', 'access_token', 'refreshToken', 'access_token_expire', 'alertExpireToken']);
        }
    }

    public function render()
    {
        $this->userAccessToken();

        if (session()->get('anydesk_callback')) {
            session()->forget('anydesk_callback');
            $this->showToastr = 'access_granted';
        } elseif (session()->get('anydesk_callback_fails')) {
            session()->forget('anydesk_callback_fails');
            $this->showToastr = 'access_granted_fails';
        } elseif (session()->get('anydesk_refreshToken_refreshed')) {
            session()->forget('anydesk_refreshToken_refreshed');
            $this->showToastr = 'refreshed_token';
        } elseif (session()->get('anydesk_refreshToken_fails')) {
            session()->forget('anydesk_refreshToken_fails');
            $this->showToastr = 'refreshed_token_fails';
        } elseif (session()->get('anydesk_revoked')) {
            session()->forget('anydesk_revoked');
            $this->showToastr = 'success_revoke';
        } elseif (session()->get('anydesk_revoked_fails')) {
            session()->forget('anydesk_revoked_fails');
            $this->showToastr = 'failed_revoke';
        } elseif (session()->has('anydesk_callback_fail_group')) {
            session()->forget('anydesk_callback_fail_group');
            $this->showToastr = 'failed_group';
        } elseif (session()->has('anydesk_callback_fail_device')) {
            session()->forget('anydesk_callback_fail_device');
            $this->showToastr = 'failed_device';
        } elseif (session()->has('anydesk_callback_fail_connection')) {
            session()->forget('anydesk_callback_fail_connection');
            $this->showToastr = 'failed_connection';
        }

        return view('livewire.tenant.test-anydesk-component');
    }

    public function checkForSessionToastr()
    {
        if ($this->showToastr == 'access_granted') {
            $this->emitSelf('retrieveFromAPIEmitedEvent');
//                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Access Granted').'!']);
        } elseif ($this->showToastr == 'access_granted_fails') {
            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Access Granted Failed').'!']);
        } elseif ($this->showToastr == 'refreshed_token') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Token Refreshed').'!']);
        } elseif ($this->showToastr == 'refreshed_token_fails') {
            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Refresh Token Failed').'!']);
        } elseif ($this->showToastr == 'success_revoke') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Success Revoke').'!']);
        } elseif ($this->showToastr == 'failed_revoke') {
            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Revoke Token Failed').'!']);
        } elseif ($this->showToastr == 'failed_group') {
            // $this->dispatchBrowserEvent('showTeamviewerManual');

            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Something went wrong with group permission') . ' ' . __('locale.Please check manual', ['route' => route('account.settings')])]);
        } elseif ($this->showToastr == 'failed_device') {
            // $this->dispatchBrowserEvent('showTeamviewerManual');

            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Something went wrong with device permission') . ' ' . __('locale.Please check manual', ['route' => route('account.settings')])]);
        } elseif ($this->showToastr == 'failed_connection') {
            // $this->dispatchBrowserEvent('showTeamviewerManual');

            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Something went wrong with connection permission') . ' ' . __('locale.Please check manual', ['route' => route('account.settings')])]);
        }

        $this->reset(['showToastr']);
    }

    // Teamviewer Retrieve Data

    public function retrieveFromAPIEmitedEvent()
    {
        $data = [];
        if ($this->ping()) {
            $groups = $this->getGroupsAPI();
            $devices = $this->getDevicesAPI();
            $connections = $this->getConnectionsAPI();
            if (!$groups || !$devices || !$connections) {
                $data = [
                    'type' => 'showToastrTeamviewerError',
                    'message' => __('locale.Something is wrong with your request. Please contact your administrator!')
                ];
//                $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.Something is wrong with your request. Please contact your administrator!')]);
            } else {
                $data = [
                    'type' => 'showToastrSuccess',
                    'message' => __('locale.Access Granted')
                ];
//                $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.Updated')]);
                $this->emitTo('tenant.customer-connections-component','retrievedDataFromAPITeamViewer');
            }
        }
        else {
            $data = [
                'type' => 'showToastrTeamviewerError',
                'message' => __('locale.The access token you provide isn\'t valid.')
            ];
//            $this->dispatchBrowserEvent('showToastrTeamviewerError', ['message' => __('locale.The access token you provide isn\'t valid.')]);
        }
        $this->dispatchBrowserEvent($data['type'], ['message' => $data['message']]);
    }

    // check if API is available
    public function ping(): bool
    {
        return RetrieveDataFromAPI::ping($this->access_token);
    }

    // retrieve Groups
    public function getGroupsAPI():bool
    {
        return RetrieveDataFromAPI::groups($this->access_token);
    }

    // retrieve Devices
    public function getDevicesAPI(): bool
    {
        return RetrieveDataFromAPI::devices($this->access_token);
    }

    // retrieve Connections
    public function getConnectionsAPI(): bool
    {
        return RetrieveDataFromAPI::connections($this->access_token);
    }

    public function reload()
    {
        $this->emit('refresh');
    }
}
