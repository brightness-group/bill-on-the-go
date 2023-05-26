<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class DeleteTenantTeamviewerAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant_delete:access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For delete the tenants access token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companies = Company::all();
        foreach ($companies as $company) {
            $company->anydesk_access_token = null;
            $company->anydesk_refresh_token = null;
            $company->anydesk_access_token_for_expire_check = null;
            $company->save();
        }
    }
}
