<?php

namespace App\Traits;

use App\Helpers\Helper;
use Tenancy\Environment;
use Tenancy\Facades\Tenancy;

trait OnTenantIfDefined
{
    private $isStatic = false;

    public function setConnectionName($connection = 'mysql')
    {
        $this->connection = $connection;

        $this->isStatic = true;
    }

    public function getConnectionName()
    {
        \Artisan::call('cache:forget spatie.permission.cache');

        if (Tenancy::getTenant()) {
            $connectionName = Environment::getTenantConnectionName();

            if (!Helper::isAdmin()) {
                if (!$this->isStatic) {
                    $this->connection = $connectionName;
                }

                config(['permission.models.permission' => \App\Models\Tenant\Permission::class]);
                config(['permission.models.role' => \App\Models\Tenant\Role::class]);
            } else {
                if (!$this->isStatic) {
                    $this->connection = 'mysql';
                }

                config(['permission.models.permission' => \Spatie\Permission\Models\Permission::class]);
                config(['permission.models.role' => \Spatie\Permission\Models\Role::class]);
            }

            return $connectionName;
        } else {
            if (
                app()->runningInConsole() &&
                !empty($_SERVER['argv'][1]) &&
                str_contains($_SERVER['argv'][1], 'tenants:update') &&
                APP_EDITION == 'bdgo'
            ) {
                $connectionName   = Environment::getTenantConnectionName();
            } else {
                $connectionName   = 'mysql';
            }

            $this->connection = $connectionName;

            return $connectionName;
        }
    }
}
