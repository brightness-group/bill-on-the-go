<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant\Customer;
use Illuminate\Console\Command;
use Tenancy\Facades\Tenancy;

class RandomGroupIdAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:bdgogid_assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check for null bdgogid and assign a generated one';

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
            $this->getUnrelatedGroupId();
        }
    }

    public function getGroupIdGenerated(): string
    {
        $randomGroupId = $this->generateRandomOwnGroupId();
        if (Customer::query()->where('bdgogid',$randomGroupId)->exists())
            $this->getGroupIdGenerated();
        else
            return $randomGroupId;
    }

    public function generateRandomOwnGroupId(): string
    {
        return 't' . Tenancy::getTenant()->getTenantKey() . '-g' . strtolower($this->generateRandomString(9));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('123456789'),1,$length);
    }

    public function getUnrelatedGroupId()
    {
        $customers = Customer::query()->whereNull('bdgogid')->get();
        if (count($customers)) {
            $customers->filter(function ($item) {
                $random = $this->getGroupIdGenerated();
                $item->bdgogid = $random;
                $item->save();
            });
        }
    }

}
