<?php

namespace App\Listeners;

use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class ConfigureTenantMigrations
{
    public function handle(ConfigureMigrations $event)
    {
        if ($event->event->tenant) {
            if ($event->event instanceof Deleted) {
                $event->disable();
            }
            else {
                $event->path(database_path('tenant/migrations'));

                if (APP_EDITION == 'bdgo') {
                    $event->path(database_path('tenant/migrations/' . APP_EDITION));
                }
            }
        }
    }
}
