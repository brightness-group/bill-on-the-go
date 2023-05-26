<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant\ConnectionReport;
use App\Services\OverlapsEvaluation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Tenancy\Facades\Tenancy;

class RebuildTenantsOverlapedConnectionsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants_rebuild:overlapped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild all the columns related with overlapped records';

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
            $exec = new OverlapsEvaluation(null);
            $exec::rebuildOverlappedConnections($company->id);
        }
    }
}
