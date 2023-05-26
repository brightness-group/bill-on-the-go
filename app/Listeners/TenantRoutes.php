<?php

namespace App\Listeners;

use Tenancy\Affects\Routes\Events\ConfigureRoutes;

class TenantRoutes
{
    public function handle(ConfigureRoutes $event)
    {
        $appEdition = APP_EDITION;

        if ($event->event->tenant) {
            $event
                ->flush()
                ->fromFile(['middleware' => ['web']], base_path("/routes/{$appEdition}/tenant.php"))
                ->fromFile(['prefix' => 'api','middleware' => ['api']], base_path("/routes/{$appEdition}/api_tenant.php"));
        }
    }

}
