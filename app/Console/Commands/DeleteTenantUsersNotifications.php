<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant\User;
use Illuminate\Console\Command;
use Tenancy\Facades\Tenancy;

class DeleteTenantUsersNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant_delete:users_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For delete all the registers from users notifications';

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
            $users = User::all();
            foreach ($users as $user)
                $user->notifications()->delete();
        }
    }
}
