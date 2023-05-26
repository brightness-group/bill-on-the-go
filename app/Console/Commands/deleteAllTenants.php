<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class deleteAllTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to delete all tenants';

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
            
            $companyModel = $company->first();
            $companyModel->delete();
            
        }
    }
}
