<?php

namespace App\Listeners;

use App\Models\Tenant\Sanctum\PersonalAccessToken;
use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Facades\Tenancy;
use App\Models\Tenant\Permission as SpatiePermission;
use App\Models\Tenant\Role as SpatieRole;

class ConfigureTenantModels
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected $modelRole = SpatieRole::class;
    protected $modelPermission = SpatiePermission::class;
    protected $modelPersonalAccessToken = PersonalAccessToken::class;


    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ConfigureModels $event)
    {
        if($event->event->tenant)
        {
             $event->setConnection(
                 $this->modelRole,
                 Tenancy::getTenantConnectionName()
             );
            $event->setConnection(
                $this->modelPermission,
                Tenancy::getTenantConnectionName()
            );
            $event->setConnection(
                $this->modelPersonalAccessToken,
                Tenancy::getTenantConnectionName()
            );
        }
    }
}
