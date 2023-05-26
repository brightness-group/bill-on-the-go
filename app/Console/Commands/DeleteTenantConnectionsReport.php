<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant\BorderLimitEvaluated;
use App\Models\Tenant\ConnectionReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tenancy\Facades\Tenancy;

class DeleteTenantConnectionsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants_delete:connections_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For delete all the Connections Report Data';

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
            Tenancy::setTenant($company);
            Schema::connection('tenant')->disableForeignKeyConstraints();
            DB::connection('tenant')->table('border_limit_evaluateds')->truncate();
            DB::connection('tenant')->table('connection_reports')->truncate();
            Schema::connection('tenant')->enableForeignKeyConstraints();
        }
    }
}
