<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Affects\Connections\Events\Drivers\Configuring;

class ConfigureTenantConnection
{
    public function handle(Configuring $event)
    {
        $event->useConnection('mysql', $event->defaults($event->tenant));
    }
}
