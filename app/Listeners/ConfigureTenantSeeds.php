<?php

namespace App\Listeners;

use Database\Seeders\PermissionsForTenantTableSeeder;
use Database\Seeders\ProductForTenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Hooks\Migration\Events\ConfigureSeeds;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class ConfigureTenantSeeds
{
    /**
     * Create the event listener.
     *
     * @return void
     */
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
    public function handle(ConfigureSeeds $event)
    {
        if ($event->event instanceof Updated) {
            $event->seed(PermissionsForTenantTableSeeder::class)->disable();
        }
        if (!$event->event instanceof Deleted && !$event->event instanceof Updated) {
            $event->seed(PermissionsForTenantTableSeeder::class);
        }
    }
}
