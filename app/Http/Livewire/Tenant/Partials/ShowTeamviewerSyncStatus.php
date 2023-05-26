<?php

namespace App\Http\Livewire\Tenant\Partials;

use App\Jobs\TeamviewerRetriveFromAPIJob;
use App\Models\Company;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\Tariff;
use App\Services\RetrieveDataFromAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class ShowTeamviewerSyncStatus extends Component
{
    public $connected = false;
    public $access_token = null;
    public $refreshToken = null;
    public $alertExpireToken = false;
    public $access_token_expire = false;
    public $is_sync_disabled = false;
    public $sync_button_message = '';
    public $batchProgress = 0;

    public $listeners = [
        'retrieveAPIDataOnEvent' => 'retrieveFromAPI',
        'refreshComponentAfterSpinnerWindow' => '$refresh',
    ];


    public function mount()
    {
        $this->userAccessToken();
    }

    public function userAccessToken()
    {
        $this->access_token = Tenancy::getTenant()->anydesk_access_token;
        $this->refreshToken = Tenancy::getTenant()->anydesk_refresh_token;

        if (Tenancy::getTenant()->tv_sync_batch_id) {
            $batch = Bus::findBatch(Tenancy::getTenant()->tv_sync_batch_id);
            $this->is_sync_disabled = !empty($batch) && !$batch->finished();
            if ($batch) {
                $this->batchProgress = $batch->progress();
            }
        } else {
            $this->is_sync_disabled = false;
        }

        if (!empty($this->access_token)) {
            $this->access_token_expire = (Tenancy::getTenant()->anydesk_access_token_for_expire_check)->diffInHours(now()) >= 24;
        }

        if (!empty($this->access_token) && !$this->access_token_expire) {
            // Check ping.
            $ping = RetrieveDataFromAPI::ping($this->access_token);

            $this->connected = $ping;

            $this->reset(['alertExpireToken']);

        } elseif ($this->access_token_expire) {
            $this->alertExpireToken = true;

            $this->reset(['connected', 'access_token']);
        } else {
            $this->reset(['connected', 'access_token', 'refreshToken', 'access_token_expire', 'alertExpireToken']);
        }

        $this->sync_button_message = $this->is_sync_disabled
            ? __('locale.Synchronization is in progress') . '(' . $this->batchProgress . '%)'
            : ($this->connected ? __('locale.Synchronize Teamviewer data') : __('locale.Please connect to anydesk'));
    }

    public function render()
    {
        return view('livewire.tenant.partials.show-anydesk-sync-status')
            ->extends('tenant.layouts.contentLayoutMaster')
            ->section('content');
    }

    public function ping()
    {
        return RetrieveDataFromAPI::ping($this->access_token);
    }
}
