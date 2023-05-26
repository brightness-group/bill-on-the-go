<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\User;
use Illuminate\Console\Command;
use Tenancy\Facades\Tenancy;

class UsersTenantToSharedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:users_to_shared_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replicate the current tenant users to shared_users table';

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
            $users = null;
            Tenancy::setTenant($company);
            $users = User::all();
            foreach ($users as $user) {
                SharedUser::create([
                    'id' => (string)$user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]);
            }
        }
    }
}
