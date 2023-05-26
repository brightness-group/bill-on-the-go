<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\App;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tenant\Events\Updated;

class RunTenantsMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tenants migrations';

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
        foreach ($companies as $tenant) {
            App::make(Dispatcher::class)->dispatch(new Updated($tenant));
        }
    }
}
