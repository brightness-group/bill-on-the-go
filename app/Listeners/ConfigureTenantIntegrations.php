<?php

namespace App\Listeners;

use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Illuminate\Support\Facades\URL;

class ConfigureTenantIntegrations
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
    public function handle(ConfigureConfig $event)
    {
        if($tenant = $event->event->tenant)
        {
            //APP_URL && ASSET_URL
            $tenantURL = $tenant->generateFullURL();
            $event->set('auth.defaults.guard', 'tenant');
            $event->set('fortify.guard', 'tenant');
            $event->set('app.app_url', $tenantURL);
            $event->set('app.asset_url', $tenantURL);
        }
    }
//        if($tenant = $event->event->tenant)
//        {
//            $subDomainBase = URL::to('/');
//
//            $event->set('app.url', $subDomainBase);
//        }
    // }
}
